<?php

require_once "framework/Model.php";
require_once "Note.php";
require_once "User.php";

class TextNote extends Note
{
    public function __construct(
        public int $id,
        public string $title, 
        public ?string $content = NULL,

    ) {
    }

    public function get_type() : string {
        return TypeNote::TN;
    }

}
