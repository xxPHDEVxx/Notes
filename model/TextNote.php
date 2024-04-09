<?php
require_once "framework/Model.php";
require_once "Note.php";
require_once "User.php";

class TextNote extends Note {
  
    public ?string $content = null ;

    public function get_type() : string {
        return TypeNote::TN;
        
    }
    public function get_note() : Note |false {
        $query = self::execute("SELECT * FROM Notes JOIN text_notes ON notes.id = text_notes.id WHERE notes.id = :id", ["id" => $this->note_id]);
        $data = $query->fetch(); 
        if($query->rowCount() == 0) {
            return false;
        }else {
            return new TextNote($data['id'], $data['title'] , $data['owner'], $data['created_at'],$data['pinned'], $data['archived'], $data['weight'],$data['edited_at']);
        }

    }
    public function get_content() : String | null{
        $dataQuery = self::execute("SELECT content FROM text_notes WHERE id = :note_id", ["note_id" => $this->note_id]);
        $content = $dataQuery->fetchColumn(); 
        return $content;
    }
    
    public static function get_note_by_id(int $note_id) : Note |false {

        $query = self::execute("SELECT * FROM notes WHERE id = :id", ["id" => $note_id]);
        $data = $query->fetch();
        if(count($data) !== 0) { 
            return new TextNote( $data['id'] , $data['title'],  $data['owner'],  $data['created_at'], 
                                    $data['pinned'],  $data['archived'], $data['weight'], $data['edited_at']);
        } 
                                 
        }
    public function isPinned() : bool {
        return $this->pinned;
    }
 

        
    
}