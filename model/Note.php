<?php

require_once "framework/Model.php";
require_once "User.php";
require_once "TextNote.php";
require_once "ChecklistNote.php";

enum TypeNote {
    const TN = "TextNote";
    const CLN = "ChecklistNote";
}


 class Note extends Model
{
    private String $title;
    private int $owner;
    private string $created_at;
    private bool $pinned;
    private bool $archived;
    private int $weight;
    private ?string $edited_at = NULL;
    private ?int $note_id = NULL;

    public function __construct( $initial_title, $initial_owner, $initial_created_at, $initial_pinned, $initial_archived, $initial_weight, $initial_edited_at, $initial_note_id
    ) {
        $this->title = $initial_title ;
        $this->owner = $initial_owner;
        $this->created_at= $initial_created_at; 
        $this->pinned = $initial_pinned;
        $this->archived = $initial_archived;
        $this->weight = $initial_weight ;
        $this->edited_at = $initial_edited_at; 
        $this->note_id = $initial_note_id;
    
    }


    public static function get_notes(User $user, bool $pinned) : array {
        $pinnedCondition = $pinned ? '1' : '0';

        $query = self::execute("SELECT * FROM notes WHERE owner = :ownerid AND archived = 0 AND pinned = :pinned ORDER BY -weight" , ["ownerid" => $user->id, "pinned" => $pinnedCondition]);
        $data = $query->fetchAll();
        $all_notes = [];

        foreach ($data as $row) {
            $all_notes[] = new Note($row['title'],$row['owner'],$row['created_at'], $row['pinned'], $row['archived'], $row['weight'], $row['edited_at'],$row['id'] );
        }

        $notes = [];

        
        foreach ($all_notes as $note) {
            $query_cln = self::execute("SELECT id from checklist_notes where id = :id ", ["id" => $note->note_id]);
            if ($query_cln->rowCount() == 0) {
                $query_text = self::execute("SELECT content FROM text_notes WHERE id = :note_id", ["note_id" => $note->note_id]);
                $data_text = $query_text->fetchColumn();         
                $notes[] = new TextNote($note,$note->note_id,$data_text); 
                    } else {
                $notes[]= self::get_checklist_note($note,$note->note_id);
            }
            
         }
        return $notes;
    }
    

    public static function get_checklist_note(Note $note,int $id) : ChecklistNote {
        $content = new ChecklistNote($note,$id); 

        return $content;
    }

    public static function get_notes_pinned(User $user) : array {
        return self::get_notes($user, true);
    }

    public static function get_notes_unpinned(User $user) : array {
        return self::get_notes($user, false);
    }

    public static function get_note_by_id(int $note_id) : Note |false {
        $query = self::execute("select * from Notes where note_id = :id", ["id" => $note_id]);
        $data = $query->fetch(); 
        if($query->rowCount() == 0) {
            return false;
        }else {
            return new Note($data['note_id'], $data['title'] , $data['owner'], $data['created_at'], $data['edited_at'], $data['pinned'], $data['archived'], $data['weight']);
        }

    }

    public function getTitle() : string {
        return $this->title;
    }

    public function setTitle(string $title) : void {
        $this->title = $title;
    }

    public function  getOwner() {
        return $this->owner;
    }

    public function setOwner(int $owner) {
        $this->owner = $owner;
    }

    public function  getCreated_at() : string {
        return $this->created_at;
    }

    public function  setCreated_at(String $created_at): void {
        $this->created_at = $created_at;
    }

    public function isPinned() : bool {
        return $this->pinned;
    }

    public function setPinned(bool $pinned) : void {
        $this->pinned = $pinned;
    }

    public function  isArchived() : bool {
        return $this->archived;
    }

    public function  setArchived(bool $archived) : void {
        $this->archived = $archived;
    }

    public function  getWeight() : int {
        return $this->weight;
    }

    public function  setWeight(int $weight) : void {
        $this->weight = $weight;
    }

    public function  getEdited_at() : string {
        return $this->edited_at;
    }

    public function setEdited_at(string $edited_at) : void {
        $this->edited_at = $edited_at;
    }

    public function  getNote_id() : int {
        return $this->note_id;
    }

    public function  setNote_id(int $note_id) : void {
        $this->note_id = $note_id;
    }





    public function delete(User $initiator) : Note |false {
        if($this->owner == $initiator) {
            self::execute("DELETE FROM Notes WHERE id = :note_id", ['note_id' => $this->note_id]);
            return $this;
        }
        return false;
    }

    public function validate() : array {
        $errors = [];

        return $errors;
    }
    public function persist() : Note|array {
        if($this->note_id == NULL) {
            $errors = $this->validate();
            if(empty($errors)){
                self::execute('INSERT INTO Notes (title, owner, pinned, archived, weight) VALUES (:title, :owner,:pinned,:archived,:weight)', 
                               ['title' => $this->title,
                                'owner' => $this->owner,
                                'pinned' => $this->pinned? 1 : 0,
                                'archived' => $this->archived? 1 : 0,
                                'weight' => $this->weight,
                               ]);
                $note = self::get_note_by_id(self::lastInsertId());
                $this->note_id = $note->note_id;
                return $this;
            } else {
                return $errors; 
            }
        } else {
            //on ne modifie jamais les notes : pas de "UPDATE" SQL.
            throw new Exception("Not Implemented.");
        }
    }

    public function get_title(): string {
        return $this->title;
    }
    public function isMax(int $id): int {
        $query = self::execute("SELECT id, MAX(weight) from notes where id = :note_id group by id" , ["note_id" => $id]);
        $data = $query->fetch();
        return $data['id'];
    }
    public function max_weight(int $id) : float {
        $query = self::execute("SELECT MAX(weight) from notes where owner = :id group by owner" , ["id" => $id]);
        $data = $query->fetch();
        return $data['MAX(weight)'];
    }

    public function min_weight(int $id) : float {
        $query = self::execute("SELECT MIN(weight) from notes where owner = :id group by owner" , ["id" => $id]);
        $data = $query->fetch();
        return $data['MIN(weight)'];
    }
}
