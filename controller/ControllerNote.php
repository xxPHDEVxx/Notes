<?php
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerNote extends Controller {
    public function index() : void {

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
    public function pin() : void {
        if(isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note($note_id);
            $note->pin();
           
}
}
public function unpin() : void {
    if(isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
        $note_id = $_GET["param1"];
        $note = Note::get_note($note_id);
        $note->unpin();
       
}
}
 

   

}