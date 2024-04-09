<?php

require_once "framework/Model.php";
require_once "User.php";
require_once "TextNote.php";
require_once "ChecklistNote.php";

enum TypeNote {
    const TN = "TextNote";
    const CLN = "ChecklistNote";
}


abstract class Note extends Model
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
   /* public function get_title() : string {
        return $this->title;
    }

    public function setTitle(string $title) : void {
        $this->title = $title;
    }

    public function  getOwner() {
        return $this->owner;
    }

    public function setOwner(User $owner) {
        $this->owner = $owner;
    }*/

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


   

    

   /* public function setPinned(bool $pinned) : void {
        $this->pinned = $pinned;
    }

    public function  isArchived() : bool {
        return $this->archived;
    }

    public function  setArchived(bool $archived) : void {
        $this->archived = $archived;
    }*/

    public function  get_weight() : int {
        return $this->weight;
    }

    public function  set_weight(int $weight) : void {
        $this->weight = $weight;
    }

  /*  public function  getEdited_at() : string {
        return $this->edited_at;
    }

    public function setEdited_at(string $edited_at) : void {
        $this->edited_at = $edited_at;
    }*/

    

    


    private static function get_notes(User $user, bool $pinned) : array {
        $pinnedCondition = $pinned ? '1' : '0';

        $notes = [];
        $query = self::execute("SELECT * FROM notes WHERE owner = :ownerid AND archived = 0 AND pinned = :pinned ORDER BY -weight" , ["ownerid" => $user->id, "pinned" => $pinnedCondition]);
        $notes = $query->fetchAll();
        $content_checklist = [];
       foreach ($notes as &$row) {
            $dataQuery = self::execute("SELECT content FROM text_notes WHERE id = :note_id", ["note_id" => $row["id"]]);
            $content = $dataQuery->fetchColumn(); 
          
            if(!$content) {
                $dataQuery = self::execute("SELECT content, checked FROM checklist_note_items WHERE checklist_note = :note_id order by checked, id ", ["note_id" => $row["id"]]);
                $content_checklist = $dataQuery->fetchAll();
            }
            $row["content"] = $content;
            $row["content_checklist"] = $content_checklist;
        }

        return $notes;
    }


    public static function get_notes_pinned(User $user) : array {
        return self::get_notes($user, true);
    }

    public static function get_notes_unpinned(User $user) : array {
        return self::get_notes($user, false);
    }

    public static function get_note_by_id(int $note_id) : Note |false {

        $query = self::execute("SELECT * FROM notes WHERE id = :id", ["id" => $note_id]);
        $data = $query->fetch(); 
        if($query->rowCount() == 0) {
            return false;
        }else {

            return new Note( $data['title'] , 
            User::get_user_by_id($data['owner']), 
            $data['created_at'], 
            $data['pinned'], 
            $data['archived'], 
            $data['weight'], 
            $data['edited_at'], 
            $data['id']);
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
                self::execute('INSERT INTO Notes (title, owner, pinned, archived, weight) VALUES (:author,:recipient,:body,:private)', 
                               ['tilte' => $this->title,
                                'owner' => $this->owner,
                                'pinned' => $this->pinned? 1 : 0,
                                'archived' => $this->archived? 1 : 0,
                                'weight' => $this->weight,
                               ]);
                $note = self::get_note(self::lastInsertId());
                $this->note_id = $note->note_id;
                return $this;
            } else {
                return $errors; 
            }
        } else {
            //on ne modifie jamais les messages : pas de "UPDATE" SQL.
            throw new Exception("Not Implemented.");
        }
    }

    




    public function is_weight_unique(int $id): int {
        $query = self::execute("SELECT id, MAX(weight) from notes where id = :note_id group by id" , ["note_id" => $id]);
        $data = $query->fetch();
        return $data['id'];
    }


    public function get_note_up(User $user,int $note_id, int $weight, bool $pin): Note | false {
        $query = self::execute("
        SELECT * FROM notes n
        WHERE owner = :ownerid AND n.id <> :note_id AND archived = 0 AND pinned = :pin AND weight > :weight 
        ORDER BY weight LIMIT 1
        ", ["ownerid" => $user->id, "note_id" => $note_id, "pin" => $pin, "weight" => $weight]);
    
        $data = $query->fetch();
        if (!$data) {
            return false;
        }

        $note_up = new Note(
            $data['title'],
            $data['owner'],

            $data['created_at'],
            $data['pinned'],
            $data['archived'],
            $data['weight'],
            $data['edited_at'],
            $data['id']
        );
    
        return $note_up;
    } 

     public function get_note_down(User $user,int $note_id, int $weight, bool $pin): Note | false {
         $query = self::execute("
         SELECT * FROM notes n
         WHERE owner = :ownerid AND n.id <> :note_id AND archived = 0 AND pinned = :pin AND weight < :weight 
         ORDER BY -weight LIMIT 1
         ", ["ownerid" => $user->id, "note_id" => $note_id, "pin" => $pin, "weight" => $weight]);
    
         $data = $query->fetch();
         if (!$data) {
             return false;
         }

         $note_down = new Note(
            $data['title'],
            $data['owner'],
             $data['created_at'],
             $data['pinned'],
             $data['archived'],
             $data['weight'],
             $data['edited_at'],
             $data['id']
         );
    
         return $note_down;
     } 

     public function move_db(Note $second) : Note {
         $weight_second = $second->get_weight();
         $second_id = $second->note_id;
         self::execute('UPDATE notes SET weight = :weight_note2 WHERE id = :id_note1', 
         ['id_note1' => $this->note_id, 'weight_note2' => $weight_second]);
         self::execute('UPDATE notes SET weight = :weight_note1 WHERE id = :id_note2', ['id_note2' => $second_id, 'weight_note1' => $this->weight]);


         return $this;
     }

}
