<?php
require_once "framework/Model.php";
require_once "User.php";
require_once "Note.php";

class NoteShare extends Model {
    public function __construct(public int $note, public int $user, public bool $editor) {

    }
    
    public function isShared_as_editor(int $userid) : bool {
        $query = self::execute("SELECT * FROM note_shares WHERE note = :id and user =:userid and editor = 1", ["id" => $this->note, "userid"=>$userid]);
        $data = $query->fetchAll();
        return count($data) !== 0;
    }   
    
}