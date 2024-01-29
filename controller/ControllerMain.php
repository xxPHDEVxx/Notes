<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";



class ControllerMain extends Controller {

    public function index() : void {
       if($this->user_logged()) {
            $this->redirect("user", "my_archives");
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

public function logout() : void {
    $user = User :: get_user_by_id(2);

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (isset($_POST['logout'])){
            $this->logout();
        }
    }

    (new View("logout"))->show(["user" => $user]);

}



public function signup() : void {
    $mail = '';
    $full_name = '';
    $password = '';
    $password_confirm = '';
    $errors =[];

    if(isset($_POST['mail']) && isset($_POST['full_name']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
        $mail = $_POST['mail'];
        $full_name = $_POST['full_name'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        $user = new User($mail, Tools::my_hash($password), $full_name);
        $errors = User::validate_unicity($mail);
        $errors = array_merge($errors, $user->validate());
        $errors = array_merge($errors, $user->validate_name());
        $errors = array_merge($errors, User::validate_passwords($password, $password_confirm));

        if(count($errors) == 0) {
            $user->persist();
            $this->log_user($user);
        }
    }
    (new View("signup"))->show(["mail"=>$mail, "full_name"=>$full_name, "password" => $password, "password_confirm" => $password_confirm, "errors"=>$errors]);
}

}
