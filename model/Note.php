<?php

require_once "framework/Model.php";
require_once "User.php";

class Note extends Model
{
    public function __construct(
        public ?int $note_id = NULL,
        public String $title,
        public User $owner,
        public string $created_at,
        public ?string $edited_at = NULL,
        public bool $pinned,
        public bool $archived,
        public int $weight
    ) {
    }
}
