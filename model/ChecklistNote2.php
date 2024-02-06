<?php

require_once "framework/Model.php";
require_once "Note2.php";
require_once "ChecklistNote2.php";
require_once "ChecklistNoteItem2.php";

class ChecklistNote2 extends Note2
{
    public function __construct(
    
        private Note2 $note,
        public int    $id

    ) {
    }

    public function get_items() : array {

        return ChecklistNoteItem2::get_items($this);
    }


    public function get_type() : string {
        return TypeNote::CLN;
    }
    public function get_infos() : Note2 {
        return $this->note;
    }

}
