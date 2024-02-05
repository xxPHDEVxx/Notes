<?php

require_once "framework/Model.php";
require_once "Note.php";
require_once "ChecklistNote2.php";
require_once "ChecklistNoteItem.php";

class ChecklistNote2 extends Note
{
    public function __construct(
    
        private Note $note,
        public int $id

    ) {
    }

    public function get_items() : array {

        return ChecklistNoteItem::get_items($this);    
    }


    public function get_type() : string {
        return TypeNote::CLN;
    }
    public function get_infos() : Note {
        return $this->note;
    }

}
