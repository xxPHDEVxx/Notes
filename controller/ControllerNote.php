<?php
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerNote extends Controller {
    public function index() : void {

    }

    public function get_created_time(int $note_id) : String {
        $created_date = Note::get_created_at($note_id);
        return $this->get_elapsed_time($created_date);
      
    }
    public function get_edited_time(int $note_id) : String | bool {
        $edited_date = Note::get_edited_at($note_id);
        return $edited_date != null ? $this->get_elapsed_time($edited_date) : false;
    }   


    public function get_elapsed_time(String $date) : String {
            $localDateNow = new DateTime();
            $dateTime = new DateTime($date);
            $diff = $localDateNow->diff($dateTime);
            $res = '';
            if($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0 && $diff->i == 0) {
                $res = $diff->s. "secondes ago.";
            }
            elseif($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0  && $diff->i != 0) {
                $res = $diff->i." minutes ago.";
            }
            elseif($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h != 0 ){
                $res = $diff->h." hours ago.";
            }
            elseif ($diff->y == 0 && $diff->m == 0 && $diff->d != 0 ){
                $res = $diff->d." days ago.";

            }
            elseif ($diff->y == 0 && $diff->m != 0 ){
                $res = $diff->m . " month ago.";
            }
            else if($diff->y != 0){
                $res = $diff->y." years ago."; 
            }
            return $res;

    }

    public function open_note() : void{
        if(isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note($note_id);
            $user_id = $this->get_user_or_redirect()->id;
            $archived = $note->in_My_archives($user_id);
            $isShared = $note->isShared($user_id);
            $body = $note->get_text_note($note_id);

        }
        (new View("open_text_note"))->show(["note"=>$note,"note_id"=>$note_id,"created"=>$this->get_created_time($note_id), "edited"=>$this->get_edited_time($note_id)
                                            , "archived" =>$archived, "isShared"=>$isShared, "note_body_text" => $body]);
    }
   

    public function archive() : void {
        if(isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note($note_id);
            $note->archive();
            $this->redirect();

    }
}
    
    public function unarchive() : void {
        if(isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note($note_id);
            $note->unarchive();
            $this->redirect();
        }

    }

   

}