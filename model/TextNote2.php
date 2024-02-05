<?php

require_once "framework/Model.php";
require_once "Note2.php";
require_once "User.php";

class TextNote2 extends Note2
{
    public function __construct(
        private Note2  $note,
        public int     $id,
        public ?string $content = NULL,

    ) {
    }

    public function get_type() : string {
        return TypeNote::TN;
    }

    public function get_infos() : Note2 {
        return $this->note;
    }
}
