<?php
require_once "framework/Model.php";
require_once "Note.php";

class User extends Model {
    
    public function __construct(public string $mail, public string $hashed_password, public string $full_name, public ?string $role = "user", public ?int $id = NULL){

    }
   

    public static function get_user_by_id(int $user_id) : User|false {
        $query = self::execute("SELECT * FROM Users where id = :id", ["id" =>$user_id]);
        $data = $query->fetch(); 
        if($query->rowCount() == 0) {
            return false;
        }else {
            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["id"]);
        }
        
    }
  

     public function persist() : User {
        if($this->id == NULL) {
            self::execute("INSERT INTO Users(mail,hashed_password,full_name,role) VALUES (:mail,:hashed_password,:full_name,:role)",
                        ["mail"=>$this->mail, "hashed_password"=>$this->hashed_password, "full_name"=>$this->full_name, "role"=>$this->role]);
            $user = self::get_user_by_id(self::lastInsertId());
            $this->id = $user->id;            
        }else
            self::execute("UPDATE Users SET mail=:mail, hashed_password=:hashed_password, full_name=:full_name, role=:role WHERE id=:id ",
                       ["mail"=>$this->mail, "hashed_password"=>$this->hashed_password, "full_name"=>$this->full_name, "role"=>$this->role]);
        return $this;
    }

    public static function get_users() : array {
        $query = self::execute("SELECT * FROM Users", []);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $result[] = new User($row["mail"], $row["hashed_password"], $row["full_name"], $row["role"]);
        }
        return $results;
    }

    public function get_notes() : array {
        return Note::get_notes($this);
    }

}