<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";
require_once "framework/Tools.php";

class ControllerMain extends Controller {
    const UPLOAD_ERR_OK = 0;
    public function index() : void {
       
        if($this->user_logged()) {
            $this->redirect("note", "index");
            
        } else {
            $this->login();
        }
    }

public function login() : void {
    $mail = '';
    $password = '';
    $errors = [];
    if(isset($_POST['mail']) && isset($_POST['password'])) {
        $mail = $_POST['mail'];
        $password = $_POST['password'];

        $errors = User::validate_login($mail, $password);
        if (empty($errors)) {
            $this->log_user(User::get_user_by_mail($mail));
        }
}
    (new View("login"))->show(["mail" => $mail, "password" => $password, "errors" => $errors]);

}
}
