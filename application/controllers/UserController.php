<?php
    namespace application\controllers;

    class UserController extends Controller {
        public function signin() {
            switch(getMethod()) {
                case _GET:
                    return "user/signin.php";
                case _POST:
                    $email = $_POST["email"];
                    $pw = $_POST["pw"];
                    $param = ["email" => $email];
                    $dbUser = $this->model->selUser($param);
                    if(!$dbUser || !password_verify($pw, $dbUser->pw)) {
                        return "redirect:signin?email={$email}&err";
                    }
                    $dbUser->pw = null;
                    $dbUser->regdt = null;
                    $this->flash(_LOGINUSER, $dbUser);
                    return "redirect:/feed/index";
                }
            }

        public function signup() {
            // print getMethod();

            switch(getMethod()) {
                case _GET:
                    return "user/signup.php";
                case _POST:
                    $param = [
                        "email" => $_POST["email"],
                        "pw" => $_POST["pw"],
                        "nm" => $_POST["nm"]
                    ];
                    $param["pw"] = password_hash($param["pw"], PASSWORD_BCRYPT);
                    $this->model->insUser($param);
                    return "redirect:signin";
                }
            }

        public function logout() {
            $this->flash(_LOGINUSER);
            return "redirect:/user/signin";
        }
    }