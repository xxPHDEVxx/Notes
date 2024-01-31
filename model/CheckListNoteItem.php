<?php 
require_once "Note.php";
require_once "CheckListNote.php";

class CheckListNoteItem extends Model {
    public function __construct(public int $id,public int $checklist_note, public string $content, 
    public bool $checked){
        
    }

    public static function get_items(int $checklist_note) : array {
        $query = self::execute("SELECT id, content, checked FROM checklist_note_items 
        WHere checklist_note = :id", ["id" => $checklist_note]);
        $data = $query->fetchAll();
        return $data;
      
    }
}