<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";

class ControllerNote extends Controller {
    public function index() : void {
        $user = User::get_user_by_id(1);
        $notes =array_merge( $user->get_notes_pinned(), $user->get_notes_unpinned());
        $names ="";
        (new View("notes"))->show(["notes" => $notes, "names"=>$names, "user"=>$user ]);
    }

    public function move_up() : void{
        
        
    }
    public function move_down() : void{

    }
}