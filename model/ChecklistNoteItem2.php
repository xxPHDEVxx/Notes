<?php

require_once "framework/Model.php";
require_once "Note2.php";
require_once "ChecklistNote2.php";

class ChecklistNoteItem2 extends Model
{
    public function __construct(

        public int $id,
        public string $content,
        public bool $checked      

    ) {
    }

    public static function get_items(ChecklistNote2 $checklistNote) : array {
        $query =  self::execute("SELECT id,content, checked 
        FROM checklist_note_items
        WHERE checklist_note = :id", ["id" => $checklistNote->id]);
        $data = $query->fetchAll();
        $items = [];
        foreach ($data as $row) {
            $items[] = new ChecklistNoteItem2(
                $row['id'],
                $row['content'],
                $row['checked']);
        }


        return $items;    
    }


}
