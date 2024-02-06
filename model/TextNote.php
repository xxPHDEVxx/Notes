<?php

require_once "framework/Model.php";
require_once "Note.php";
require_once "User.php";

class TextNote extends Note
{
    public function __construct(
        private Note $note,
        public int $id,
        public ?string $content = NULL,

    ) {
    }

    public function get_type() : string {
        return TypeNote::TN;
    }

    public function get_infos() : Note {
        return $this->note;
    }
}
