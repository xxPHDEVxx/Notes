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
        $full_names = $this->shared_by();
        (new View("archives"))->show(["archives"=>$archives, "names"=>$full_names]);
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
        $full_names = [];
        foreach($idsUnique as $userid) {
            $name = User::get_user_by_id($userid)->full_name;
            $full_names[] = $name;
        }
        return $full_names;
        
    }
  

}