<?php
require_once "framework/Model.php";
require_once "User.php";
require_once "CheckListNoteItem.php";

class CheckListNote extends Note {
    
    public function get_content() : array {
        $query = self::execute("SELECT * FROM checklist_note_items 
        WHere checklist_note = :id order by checked, id ", ["id" => $this->note_id]);
        $data = $query->fetchAll();
        return $data;
    }

    public function get_type() : string {
        return TypeNote::CLN;
    }
    public function get_note() : Note |false {
        $query = self::execute("SELECT * FROM Notes JOIN checklist_notes ON notes.id = checklist_notes.id WHERE notes.id = :id", ["id" => $this->note_id]);
        $data = $query->fetch(); 
        if($query->rowCount() == 0) {
            return false;
        }else {
            return new CheckListNote($data['id'], $data['title'] , $data['owner'], $data['created_at'],$data['pinned'], $data['archived'], $data['weight'],$data['edited_at']);
        }

    }
    
}