<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";
class ControllerSettings extends Controller
{

    public function settings(): void
    {
        $user = $this->get_user_or_redirect();
        $sharers = $user->shared_by();
        $currentPage = "settings";
        (new View("settings"))->show(["user" => $user, "sharers" => $sharers, "currentPage" => $currentPage]);
    }

    // ajouter error et validations
    public function edit_profile(): void
    {
        $user = $this->get_user_or_redirect();
        $successMessage = null;
        $errors= [];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newEmail = Tools::sanitize($_POST['email']);
            $newFullName = Tools::sanitize($_POST['fullName']);

            $errors = User::validate_edit($newEmail, $newFullName, $user);

            if (empty($errors)) {
                try {
                    if ($user->mail == $newEmail && $user->full_name == $newFullName) {
                        $successMessage = "nothing";
                        $this->redirect("settings", "success_profile", $successMessage);
                    } else {
                        $user->update_profile($newFullName, $newEmail);
                        $successMessage = "update";
                        $this->redirect("settings", "success_profile", $successMessage);
                    }
                } catch (Exception $e) {
                    $errors[] = "Error updating profile : " . $e->getMessage();
                }
            } 
        }
        (new View("edit_profile"))->show(["user" => $user, "successMessage" => $successMessage, "errors" => $errors]);

    }

    public function success_profile() {
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"])) {
            if ($_GET["param1"] == "nothing") {
                $successMessage = "Nothing to update !";
            } else {
                $successMessage = "Profile updated";
            }
            (new View("edit_profile"))->show(["user" => $user, "successMessage" => $successMessage]);
        }
    }

    public function change_password(): void
    {
        $user = $this->get_user_or_redirect();

        $successMessage = null;
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmNewPassword = $_POST['confirmNewPassword'];


            $errors = User::validate_login($user->mail, $currentPassword);

            if (empty($errors)) {

                $passwordErrors = User::validate_passwords($newPassword, $confirmNewPassword, $user);

                if (empty($passwordErrors)) {

                    try {
                        $user->setPassword($newPassword);
                        $user->updatePassword($newPassword);
                        $successMessage = "success";
                        $this->redirect("settings","success_password", $successMessage );
                    } catch (Exception $e) {
                        $errors[] = "Erreur lors de la mise à jour du mot de passe : " . $e->getMessage();
                    }
                } else {
                    $errors = array_merge($errors, $passwordErrors);
                }
            }

        } 
        (new View("change_password"))->show(["user" => $user, "successMessage" => $successMessage, "errors" => $errors]);
    }

    public function success_password() {
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"])) {
            if ($_GET["param1"] == "success") {
                $successMessage = "Password changed successfully!";
            }
            (new View("change_password"))->show(["user" => $user, "successMessage" => $successMessage]);
        }    
    }
    public function index(): void
    {
    }
}
