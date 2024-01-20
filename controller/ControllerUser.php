<?php
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerUser extends Controller {
    public function index() : void {

    }

    public function my_archives() : void {
        $user = $this->get_user_or_redirect();
        $archives = $user->get_archives();
        (new View("archives"))->show(["archives"=>$archives, "sharers"=>$this->shared_by()]);
    }


    
    public function shared_by() : array {
        $user = $this->get_user_or_redirect();
        $shared = $user->get_shared_note();
        $ids = [];
        foreach($shared as $shared_note) {
            $id = $shared_note->owner;
            $ids[]= $id;
        }
        $idsUnique = array_unique($ids);
        $sharers = [];
        foreach($idsUnique as $userid) {
            $user = User::get_user_by_id($userid);
            $sharers[] = $user;
        }
        return $sharers;
    }

    public function get_shared_by() : void {
        $shared_notes_by = [];
        $shared_notes_as_editor = [];
        $shared_notes_as_reader = [];
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $shared_by = $_GET["param1"];
            $shared_by_name = User::get_user_by_id($shared_by)->full_name;
            $shared_notes_by = $user->get_shared_by($shared_by);
            foreach($shared_notes_by as $shared) {
                if($shared["editor"] == 1)
                    $shared_notes_as_editor[] = $shared;
                else 
                    $shared_notes_as_reader[] = $shared;
            }
        }
        (new View("shared_notes"))->show(["shared_by_name"=>$shared_by_name, "shared_notes_as_editor" =>$shared_notes_as_editor,
        "shared_notes_as_reader" =>$shared_notes_as_reader,"sharers"=>$this->shared_by()]);
    }
  

}