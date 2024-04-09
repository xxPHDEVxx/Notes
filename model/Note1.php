<?php

require_once "framework/Model.php";
require_once "User.php";
require_once "TextNote.php";
require_once "checklistNote.php";

enum TypeNote1 {
    const TN = "TextNote";
    const CLN = "CheckListNote";
}

abstract class Note1 extends Model
{
    public function __construct(
        public int $note_id,
        public String $title,
        public int $owner,
        public string $created_at,
        public bool $pinned,
        public bool $archived,
        public int $weight,
        public ?string $edited_at = NULL
    ) {
    }
    public abstract function get_type();
    public abstract function get_content();
    public abstract function get_note();
  


    public static function get_created_at(int $id) : String {
        $query = self::execute("SELECT created_at from notes WHERE id = :id", ["id" => $id]);
        $data = $query->fetchColumn();
        
        return $data;
        
    }
    public static function get_edited_at(int $id) : String | null {
        $query = self::execute("SELECT edited_at from notes WHERE id = :id", ["id" => $id]);
        $data = $query->fetchColumn();
        
        return $data;
        
    }
    public function isShared_as_editor(int $userid) : bool {
        $query = self::execute("SELECT * FROM note_shares WHERE note = :id and user =:userid and editor = 1", ["id" => $this->note_id, "userid"=>$userid]);
        $data = $query->fetchAll();
        return count($data) !== 0;
    }

    public function isShared_as_reader(int $userid) : bool {
        $query = self::execute("SELECT * FROM note_shares WHERE note = :id and user =:userid and editor = 0", ["id" => $this->note_id, "userid"=>$userid]);
        $data = $query->fetchAll();
        return count($data) !== 0;
    }
    public function in_my_archives(int $userid) : int {
        $query = self::execute("SELECT archived FROM notes WHERE owner = :userid and id = :id", ["userid"=> $userid, "id"=>$this->note_id]);
        $data = $query->fetchColumn();
        return $data;
    }
    public function is_pinned(int $userid) : int {
        $query = self::execute("SELECT pinned FROM notes WHERE owner = :userid and id = :id", ["userid"=> $userid, "id"=>$this->note_id]);
        $data = $query->fetchColumn();
        return $data;
    }



 
    public static function get_archives(User $user): array {
        $archives = [];
        $query = self::execute("SELECT id, title FROM notes WHERE owner = :ownerid AND archived = 1 ORDER BY -weight" , ["ownerid" => $user->id]);
        $archives = $query->fetchAll();
        $content_checklist = [];
       foreach ($archives as &$row) {
            $dataQuery = self::execute("SELECT content FROM text_notes WHERE id = :note_id", ["note_id" => $row["id"]]);
            $content = $dataQuery->fetchColumn(); 
          
            if(!$content) {
                $dataQuery = self::execute("SELECT content, checked FROM checklist_note_items WHERE checklist_note = :note_id order by checked, id ", ["note_id" => $row["id"]]);
                $content_checklist = $dataQuery->fetchAll();
            }
            $row["content"] = $content;
            $row["content_checklist"] = $content_checklist;
        }
        return $archives;
    }
     public function archive() : void {
        self::execute("UPDATE notes SET archived = :val WHERE id = :id" , ["val" => 1, "id" =>$this->note_id]);
    }

    public function unarchive() : void {
        self::execute("UPDATE notes SET archived = :val WHERE id = :id" , ["val" => 0, "id" =>$this->note_id]);
    }
    public function pin() : void {
        self::execute("UPDATE notes SET pinned = :val WHERE id = :id" , ["val" => 1, "id" =>$this->note_id]);
    }
    public function unpin() : void {
        self::execute("UPDATE notes SET pinned = :val WHERE id = :id" , ["val" => 0, "id" =>$this->note_id]);
    }
    public static function get_note_by_id(int $note_id) : Note |false {

        $query = self::execute("SELECT content FROM text_notes where id = :id", ["id" =>$note_id]);
        $data = $query->fetchAll();
        return count($data) !== 0 ? TextNote::get_note_by_id($note_id) : CheckListNote::get_note_by_id($note_id);
        }
   

}
