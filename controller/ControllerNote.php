<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";

class ControllerNote extends Controller {
    public function index() : void {
        $user = User::get_user_by_id(1);
        $notes_pinned = $user->get_notes_pinned();
        $notes_unpinned = $user->get_notes_unpinned();
        (new View("notes"))->show(["notes_pinned" => $notes_pinned,"notes_unpinned" => $notes_unpinned ]);
    }
}