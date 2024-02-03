<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";

class ControllerSettings extends Controller
{

    public function settings(): void
    {
        $user = $this->get_user_or_redirect();

        (new View("settings"))->show(["user" => $user]);
    }

    public function edit_profile(): void
    {
        $user = User::get_user_by_id(2);
        (new View("edit_profile"))->show(["user" => $user]);
    }

    public function index(): void
    {
        (new View("index"))->show();
    }
}
