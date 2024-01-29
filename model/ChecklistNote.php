<?php

require_once "framework/Model.php";
require_once "Note.php";
require_once "ChecklistNote.php";
require_once "ChecklistNoteItem.php";

class ChecklistNote extends Note
{
    public function __construct(
    
        public string $title,
        public int $id

    ) {
    }

    public function get_items() : array {

        return ChecklistNoteItem::get_items($this);    
    }


    public function get_type() : string {
        return TypeNote::CLN;
    }

}
