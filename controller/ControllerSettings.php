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

        (new View("settings"))->show(["currentPage" => "settings", "user" => $user, "sharers" => $sharers]);
    }

    // ajouter error et validations
    public function edit_profile(): void
    {
        $user = $this->get_user_or_redirect();
        $successMessage = null;
        $errors[] = [];
        $sharers = $user->shared_by();


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newEmail = Tools::sanitize($_POST['email']);
            $newFullName = Tools::sanitize($_POST['fullName']);

            $errors = User::validateEdit($newEmail, $newFullName);


            if (empty($errors)) {

                try {
                    if ($user->mail == $newEmail && $user->full_name == $newFullName) {
                        $successMessage = "Nothing to update.";
                    } else {
                        $user->updateProfile($newFullName, $newEmail);
                        $successMessage = "Profil updated !";
                    }
                } catch (Exception $e) {
                    $errors[] = "Error updating profile : " . $e->getMessage();
                }
            } else {
                $errors = array_merge($errors);
            }
            (new View("edit_profile"))->show(["user" => $user, "successMessage" => $successMessage, "errors" => $errors, "sharers" => $sharers]);
        } else {
            (new View("edit_profile"))->show(["user" => $user, "sharers" => $sharers]);
        }
    }

    public function change_password(): void
    {
        $user = $this->get_user_or_redirect();

        $successMessage = null;
        $errors[] = [];
        $sharers = $user->shared_by();

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
        } else {
            (new View("change_password"))->show(["user" => $user, "sharers" => $sharers]);
        }
    }


    public function index(): void
    {
    }
}
