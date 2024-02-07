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
        $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
        (new View("archives"))->show(["currentPage"=> "my_archives","archives"=>$archives, "sharers"=>$user->shared_by()]);
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
        $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
        (new View("shared_notes"))->show(["currentPage" => $shared_by_name, "shared_by_name"=>$shared_by_name, "shared_notes_as_editor" =>$shared_notes_as_editor,
        "shared_notes_as_reader" =>$shared_notes_as_reader,"sharers"=>$user->shared_by()]);
    }
  

}