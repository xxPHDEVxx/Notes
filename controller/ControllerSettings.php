<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";

class ControllerSettings extends Controller
{

    public function settings(): void
    {
        $user = $this->get_user_or_redirect();
        $sharers = "";

        (new View("settings"))->show(["user" => $user, "sharers" => $sharers]);
    }

    // ajouter error et validations
    public function edit_profile(): void
    {
        $user = $this->get_user_or_redirect();
        $sharers = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $newFullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);

            $user->updateProfile($newEmail, $newFullName);
            $this->redirect("settings", "edit_profile");
        }

        (new View("edit_profile"))->show(["user" => $user, "sharers" => $sharers]);
    }

    public function change_password(): void
    {
        $user = $this->get_user_or_redirect();

        $successMessage = null;
        $errors[] = [];
        $sharers = NULL;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmNewPassword = $_POST['confirmNewPassword'];


            $errors = User::validate_login($user->mail, $currentPassword);

            if (empty($errors)) {

                $passwordErrors = User::validate_passwords($newPassword, $confirmNewPassword);

                if (empty($passwordErrors)) {

                    try {
                        $user->setPassword($newPassword);
                        $user->updatePassword($newPassword);
                        $successMessage = "Password changed successfully!";
                    } catch (Exception $e) {
                        $errors[] = "Erreur lors de la mise à jour du mot de passe : " . $e->getMessage();
                    }
                } else {
                    $errors = array_merge($errors, $passwordErrors);
                }
            }
            (new View("change_password"))->show(["user" => $user, "successMessage" => $successMessage, "errors" => $errors, "sharers" => $sharers]);
        } else {(new View("change_password"))->show(["user" => $user, "sharers" => $sharers]);}
    }


    public function index(): void
    {
        (new View("index"))->show();
    }
}
