<?php
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";

class ControllerNote extends Controller {
    public function index() : void {
       $user = $this->get_user_or_redirect();
        $notes_pinned = $user->get_notes_pinned();
        $notes_unpinned = $user->get_notes_unpinned();
        $names ="";
        (new View("notes"))->show(["notes_pinned" => $notes_pinned,"notes_unpinned" => $notes_unpinned, "names"=>$names, "user"=>$user ]);
    }

    public function move_up() : void{
        $user = $this->get_user_or_redirect();
        if (isset($_POST["up"]) && $_POST["up"] != "") {
            $id = $_POST["up"];
            $note = Note::get_note_by_id($id);
            if ($note === false)
                throw new Exception("undefined note");
            $other = $note->get_note_up($user, $id,$note->get_weight(), $note->isPinned());
            $note->move_db($other);
            $this->redirect("note", "index");
        } else {
            throw new Exception("Missing ID");
        }

        
    }
    public function move_down() : void{
       $user = $this->get_user_or_redirect();
        if (isset($_POST["down"]) && $_POST["down"] != "") {
            $id = $_POST["down"];
            $note = Note::get_note_by_id($id);
            if ($note === false)
                throw new Exception("undefined note");
            $other = $note->get_note_down($user, $id,$note->get_weight(), $note->isPinned());
            $other->move_db($note);
            $this->redirect("note", "index");
        } else {
            throw new Exception("Missing ID");
        }
    }
    public function shares() {
        $errors = "";
        $user = $this->get_user_or_redirect();
        // $note_id = filter_var($_GET['param1'], FILTER_VALIDATE_INT);
        // if ($note_id === false) {
        //     $errors = "invalid note";
        // } else {
        //     $note = Note::get_note_by_id($note_id);
        // }

        $all_users = User::get_users();
        
        (new View("share"))->show(["all_users" => $all_users, "user" => $user]);
    }
}