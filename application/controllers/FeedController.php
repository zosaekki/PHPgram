<?php
    namespace application\controllers;

    class UserController extends Controller {
        public function index() {
            $this->addAttribute(_MAIN, "feed/index.php");
            return "template/t1.php";
        }
    }