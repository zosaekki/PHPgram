<?php
namespace application\models;
use PDO;

class FeedModel extends Model {
    function insFeed(&$param) {
        $sql = "INSERT INTO t_feed
                (location, ctnt, iuser)
                VALUES
                (:location, :ctnt, :iuser)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":location", $param["location"]);
        $stmt->bindValue(":ctnt", $param["ctnt"]);
        $stmt->bindValue(":iuser", $param["iuser"]);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    function insFeedImg(&$param) {
        $sql = "INSERT INTO t_feed_img
                (img, ifeed)
                VALUES
                (:img, :ifeed)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":img", $param["img"]);
        $stmt->bindValue(":ifeed", $param["ifeed"]);
        $stmt->execute();
    }
}