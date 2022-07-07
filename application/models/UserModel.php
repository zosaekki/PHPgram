<?php
namespace application\models;
use PDO;


//$pdo -> lastInsertId();

class UserModel extends Model {
    public function insUser(&$param) {
        $sql = "INSERT INTO t_user
                ( email, pw, nm ) 
                VALUES 
                ( :email, :pw, :nm )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":email", $param["email"]);
        $stmt->bindValue(":pw", $param["pw"]);
        $stmt->bindValue(":nm", $param["nm"]);
        $stmt->execute();
        return $stmt->rowCount();

    }
    public function selUser(&$param) {
        $sql = "SELECT * FROM t_user
                WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":email", $param["email"]);      
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function selUserProfile(&$param) {
        $feediuser = $param["feediuser"];
        $loginiuser = $param["loginiuser"];
        $sql = "SELECT iuser, email, nm, cmt, mainimg
                , (SELECT COUNT(ifeed) FROM t_feed WHERE iuser = $feediuser) AS feedCnt
                , (SELECT COUNT(fromiuser) FROM t_user_follow WHERE fromiuser = $feediuser AND toiuser = $loginiuser) AS youme
                , (SELECT COUNT(fromiuser) FROM t_user_follow WHERE fromiuser = $loginiuser AND toiuser = $feediuser) AS meyou
                , (SELECT COUNT(fromiuser) FROM t_user_follow WHERE fromiuser = $feediuser) AS follower
                , (SELECT COUNT(toiuser) FROM t_user_follow WHERE toiuser = $feediuser) AS following
                FROM t_user
                WHERE iuser = $feediuser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function updUser(&$param) {
        $sql = "UPDATE t_user
                SET moddt = now()";
        if(isset($param["mainimg"])) {
            $mainimg = $param["mainimg"];
            $sql .= ", mainimg = '$mainimg'";
        }
        if(isset($param["delMainImg"])) {
            $sql .= ", mainimg = null";
        }

        $sql .= " WHERE iuser = :iuser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":iuser", $param["iuser"]);
        $stmt->execute();
        return $stmt->rowCount();
    }


    // ----------------------------- Follow
    public function insUserFollow(&$param) {
        $sql = "INSERT INTO t_user_follow
                (fromiuser, toiuser)
                VALUES
                (:fromiuser, :toiuser)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":fromiuser", $param["fromiuser"]);
        $stmt->bindValue(":toiuser", $param["toiuser"]);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delUserFollow(&$param) {
        $sql = "DELETE FROM t_user_follow
                WHERE fromiuser = :fromiuser
                AND toiuser = :toiuser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":fromiuser", $param["fromiuser"]);
        $stmt->bindValue(":toiuser", $param["toiuser"]);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function selFeedList(&$param) {
        $sql = " SELECT A.ifeed, A.location, A.ctnt, A.iuser, A.regdt
                      , C.nm AS writer, C.mainimg
                      ,IFNULL(E.cnt, 0) AS favCnt
                      ,IF(F.ifeed IS NULL, 0, 1) AS isFav
                      -- ,case when D.ifeed IS NULL then 0 ELSE 1 END AS isFav
                  FROM t_feed A
                 INNER JOIN t_user C
                    ON A.iuser = C.iuser
                  LEFT JOIN (
                SELECT ifeed, COUNT(ifeed) AS cnt
                  FROM t_feed_fav
                 GROUP BY ifeed
                ) E
                    ON A.ifeed = E.ifeed
                  LEFT JOIN (
                SELECT ifeed
                  FROM t_feed_fav
                 WHERE iuser = :loginiuser
                ) F
                    ON A.ifeed = F.ifeed
                    WHERE C.iuser = :toiuser
                 ORDER BY A.ifeed DESC
                 LIMIT :startIdx, :feedItemCnt;
                ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":toiuser", $param["toiuser"]);
        $stmt->bindValue(":loginiuser", $param["loginiuser"]);
        $stmt->bindValue(":startIdx", $param["startIdx"]);
        $stmt->bindValue(":feedItemCnt", _FEED_ITEM_CNT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}