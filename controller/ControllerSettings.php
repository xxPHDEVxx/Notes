<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";

class ControllerSettings extends Controller
{

    // à modifier par user courant avant remise
    public function settings(): void
    {
        $user = $this->get_user_or_redirect();

        (new View("settings"))->show(["user" => $user]);
    }

    // ajouter error et validations
    public function edit_profile(): void
    {
        $user = $this->get_user_or_redirect();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $newFullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);

            $user->updateProfile($newEmail, $newFullName);
            $this->redirect("settings", "edit_profile");
        }

        (new View("edit_profile"))->show(["user" => $user]);
    }

    public function index(): void
    {
        (new View("index"))->show();
    }
}
