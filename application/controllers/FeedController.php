<?php
    namespace application\controllers;

    class FeedController extends Controller {
        public function index() {
            $this->addAttribute(_JS, ["feed/index"]);
            $this->addAttribute(_CSS, ["feed/index"]);
            $this->addAttribute(_MAIN, $this->getView("feed/index.php"));
            return "template/t1.php";
        }

        public function rest() {
            // print "method : " . getMethod() . "<br>";

            // switch(getMethod()) {
            //     case _POST:
            //         if(is_array($_FILES)) {
            //             foreach($_FILES['imgs']['name'] as $key => $value) {
            //                 print "key : {$key}, value: {$value} <br>";
            //             }
            //         }
            //         print "ctnt : " . $_POST["ctnt"] . "<br>";
            //         print "location : " . $_POST["location"] . "<br>";
            switch(getMethod()) {
                case _POST:
                    if(!is_array($_FILES) || !isset($_FILES["imgs"])) {
                        return ["result" => 0];
                    }
                    $iuser = getIuser();
                    $param = [
                        "location" => $_POST["location"],
                        "ctnt" => $_POST["ctnt"],
                        "iuser" => $iuser
                    ];
                    $ifeed = $this->model->insFeed($param);

                    foreach($_FILES["imgs"]["name"] as $key => $originalFileNm) {

                        $saveDirectory = _IMG_PATH . "/feed/" . $ifeed;
                        if(!is_dir($saveDirectory)) {
                            mkdir($saveDirectory, 0777, true); // 0777 권한 준 것
                        }
                        $tempName = $_FILES['imgs']['tmp_name'][$key];
                        $randomFileNm = getRandomFileNm($originalFileNm);
                        $param = [
                            "ifeed" => $ifeed,
                            "img" => $randomFileNm
                        ];
                        if(move_uploaded_file($tempName, $saveDirectory . "/" . $randomFileNm)) {
                            // chmod("C:/Apache24/PHPgram/static/img/profile/1/test." . $ext, 0755);
                            $this->model->insFeedImg($param);
                        }
                    }

                    return ["result" => 1];

                case _GET:
                    $page = 1;
                    if(isset($_GET["page"])) {
                        $page = intval($_GET["page"]);
                    }
                    $startIdx = ($page - 1 ) * _FEED_ITEM_CNT;
                    $param = [
                        "startIdx" => $startIdx,
                        "iuser" => getIuser()
                    ];
                    $list = $this->model->selFeedList($param);
                    foreach($list as $item) {
                        $item->imgList = $this->model->selFeedImgList($item);
                    }
                    return $list;
            }
        }
    }