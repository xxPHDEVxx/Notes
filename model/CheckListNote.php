<?php
require_once "framework/Model.php";
require_once "User.php";

class CheckListNote extends Note {


    
    public function get_items() : array {
        return CheckListNoteItem::get_items($this->note_id);
    }

    public function get_type() : string {
        return TypeNote::CLN;
    }
    public static function get_note(int $note_id) : Note |false {
        $query = self::execute("SELECT * FROM Notes JOIN checklist_notes ON notes.id = checklist_notes.id WHERE notes.id = :id", ["id" => $note_id]);
        $data = $query->fetch(); 
        if($query->rowCount() == 0) {
            return false;
        }else {
            return new CheckListNote($data['id'], $data['title'] , $data['owner'], $data['created_at'],$data['pinned'], $data['archived'], $data['weight'],$data['edited_at']);
        }

    }
    
}