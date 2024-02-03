<?php
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerOpenNote extends Controller {
    public function index() : void {
        if(isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note($note_id);
            $user_id = $this->get_user_or_redirect()->id;
            $archived = $note->in_My_archives($user_id);
            $pinned = $note->is_pinned($user_id);
            $isShared_as_editor = $note->isShared_as_editor($user_id);
            $isShared_as_reader = $note->isShared_as_reader($user_id);
            $body = $note->get_type() == "TextNote" ? TextNote::get_text_content($note_id) : CheckListNote::get_items($note_id);
            $_SESSION['previous_page'] = $_SERVER['HTTP_REFERER'];
            $back = $_SESSION['previous_page'];
        }
         ($note->get_type() == "TextNote" ? new View("open_text_note") : new View("open_checklist_note"))->show(["note"=>$note,"note_id"=>$note_id,"created"=>$this->get_created_time($note_id), "edited"=>$this->get_edited_time($note_id)
                                            , "archived" =>$archived, "isShared_as_editor"=>$isShared_as_editor,"isShared_as_reader"=>$isShared_as_reader, "note_body" => $body, "back"=>$back, "pinned"=>$pinned]);
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

  
   


    public function update_checked() : void {
    // if( isset($_POST["param1"]) && isset($_POST["param1"]) !== "" && isset($_POST["checklist_item"])){
      if(isset($_POST["check"])){
            $checklist_item_id = $_POST["check"];
            $note_id = CheckListNoteItem::get_checklist_note($checklist_item_id);
            $checked = true;
            CheckListNoteItem::update_checked($checklist_item_id, $checked);
    
      }elseif(isset($_POST["uncheck"])){
        $checklist_item_id = $_POST["uncheck"];
        $note_id = CheckListNoteItem::get_checklist_note($checklist_item_id);
        $checked = false;
        CheckListNoteItem::update_checked($checklist_item_id, $checked);
      }
      $this->redirect("note", "open_note/$note_id");
        
    }

   

}