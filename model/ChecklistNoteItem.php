<?php 
require_once "Note1.php";
require_once "CheckListNote.php";

class CheckListNoteItem extends Model {
    public function __construct(public int $id,public int $checklist_note, public string $content, 
    public bool $checked){
        
    }

    /*public static function get_items(int $checklist_note) : array {
        $query = self::execute("SELECT * FROM checklist_note_items 
        WHERE checklist_note = :id order by checked, id ", ["id" => $checklist_note]);
        $data = $query->fetchAll();
        return $data;

    }*/
    public static function update_checked(int $checklist_item_id, bool $checked) : bool{
     self::execute("UPDATE checklist_note_items SET checked =:checked WHERE id = :id", ["id"=>$checklist_item_id, "checked"=>$checked]);
        return true;
    }
    public static function get_checklist_note(int $checklist_item_id) : int{
        $query = self::execute("SELECT checklist_note FROM checklist_note_items WHERE id = :id", ["id"=>$checklist_item_id]);
        $data = $query->fetchColumn();
        return $data ;  
 }
}