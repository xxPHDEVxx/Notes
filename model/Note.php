<?php

require_once "framework/Model.php";
require_once "User.php";

class Note extends Model
{
    public function __construct(
        public int $note_id,
        public String $title,
        public User $owner,
        public string $created_at,
        public int $weight,
        public bool $pinned,
        public bool $archived,
        public ?string $edited_at = NULL
    ) {
    }


    public static function get_notes(User $user) : array {
        $query = self::execute("select * from Notes where owner = :id order by weight", ["id" => $user->id]);
        $data = $query->fetchAll();
        $notes = [];
        foreach ($data as $row) {
            $notes[] = new Note($row['id'], $row['title'] , User::get_user_by_id($row['owner']), $row['created_at'], $row['edited_at'], $row['pinned'], $row['archived'], $row['weight']);
        }
        return $notes;

    }

    public static function get_note(int $note_id) : Note |false {
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
                self::execute('INSERT INTO Notes (title, owner, pinned, archived, weight) VALUES (:author,:recipient,:body,:private)', 
                               ['tilte' => $this->title,
                                'owner' => $this->owner->id,
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

    
    public function archive() : void {
        self::execute("UPDATE notes SET archived = :val WHERE id = :id" , ["val" => 1, "id" =>$this->note_id]);
    }

}
