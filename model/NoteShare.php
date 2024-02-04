<?php

require_once "framework/Model.php";
require_once "Note.php";

class NoteShare extends Model
{
    public function __construct(
    
        private int $note_id,
        private int $user_id,
        private bool $is_editor

    ) {
    }

    public function get_note_id() : int {
        return $this->note_id;
    }

    public function set_note_id(int $id) : void {
        $this->note_id = $id;
    }

    public function get_user_id() : int {
        return $this->user_id;
    }

    public function set_user_id(int $id) : void {
         $this->user_id = $id;
    }

    public function get_is_editor() : int {
        return $this->is_editor;
    }

    public function set_is_editor(bool $editor) : void {
        $this->is_editor = $editor;
    }

    public static function get_shared_for_user(int $user_id) : array {
        $query = self::execute("SELECT * FROM note_shares WHERE user = :user_id",  ["user_id"=>$user_id]);
        $shared_by = $query->fetchAll();
        return $shared_by;
    }

    public static function get_shared_with_user(int $user_id, int $note_id) : array {
        $query = self::execute("SELECT * FROM note_shares ns 
        JOIN notes n ON ns.note = n.id 
        JOIN users u ON ns.user = u.id
        WHERE user = :user_id
        AND ns.note = :note_id",  
        ["user_id"=>$user_id, "note_id" => $note_id]);
        $note_share_with = $query->fetchAll();
        return $note_share_with;
    }


}
