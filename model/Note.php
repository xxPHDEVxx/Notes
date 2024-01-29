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
    public String $title;
    public int $owner;
    public string $created_at;
    public bool $pinned;
    public bool $archived;
    public int $weight;
    public ?string $edited_at = NULL;
    public ?int $note_id = NULL;

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

    public static function get_notes(User $user) : array {

        $query = self::execute("SELECT * FROM notes WHERE owner = :ownerid AND archived = 0 ORDER BY weight" , ["ownerid" => $user->id]);
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
                $notes[] = new TextNote($note->note_id, $note->title,$data_text); 
                    } else {
                $notes[]= self::get_checklist_note($note->title,$note->note_id);
            }
            
         }
        return $notes;
    }
    

    public static function get_checklist_note(string $title,int $id) : ChecklistNote {
        $content = new ChecklistNote($title,$id); 

        return $content;
    }

    public static function get_notes_pinned(User $user) : array {
        $query = self::execute("select n.id, n.title, t.content 
        from Notes n 
        JOIN text_notes t ON n.id = t.id 
        where owner = :id and pinned = :pin and archived = :arch 
        order by weight", ["id" => $user->id, "pin" => 1, "arch"=>0]);
        $data = $query->fetchAll();

        return $data;

    }

    public static function get_notes_unpinned(User $user) : array {
        $query = self::execute("select n.title, t.content from Notes n JOIN text_notes t ON n.id = t.id where owner = :id and pinned = :pin and archived = :arch order by weight", ["id" => $user->id, "pin" => 0, "arch"=>0]);
        $data = $query->fetchAll();

        return $data;

    }

    public static function get_notes_archived(User $user) : array {
        $query = self::execute("select * from Notes where owner = :id and archived = :arch order by weight", ["id" => $user->id, "arch"=> 1]);
        $data = $query->fetchAll();
        $notes = [];
        foreach ($data as $row) {
            $notes[] = new Note($row['id'], $row['title'] , User::get_user_by_id($row['owner']), $row['created_at'], $row['edited_at'], $row['pinned'], $row['archived'], $row['weight']);
        }
        return $notes;

    }

    public static function get_note_by_id(int $note_id) : Note |false {
        $query = self::execute("select * from Notes where note_id = :id", ["id" => $note_id]);
        $data = $query->fetch(); 
        if($query->rowCount() == 0) {
            return false;
        }else {
            return new Note($data['note_id'], $data['title'] , User::get_user_by_id($data['owner']), $data['created_at'], $data['edited_at'], $data['pinned'], $data['archived'], $data['weight']);
        }

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

}
