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
    public function get_edited_time(int $note_id) : String {
        $edited_date = Note::get_edited_at($note_id);
        return $this->get_elapsed_time($edited_date);
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

    public function open_note(){
        if(isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note = $_GET["param1"];
            $archived = Note::isArchived($note);
        }
        (new View("open_text_note"))->show(["created"=>$this->get_created_time($note), "edited"=>$this->get_edited_time($note)
                                            , "archived" =>$archived]);
    }

}