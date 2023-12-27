<?php
require_once "framework/Model.php";
require_once "Note.php";

class User extends Model {
    
    public function __construct(public string $mail, public string $hashed_password, public string $full_name, public ?string $role = "user", public ?int $id = NULL){

    }
   


    public static function get_user_by_mail(string $mail) : User|false {
        $query = self::execute("SELECT * FROM Users where mail = :mail", ["mail" =>$mail]);
        $data = $query->fetch(); 
        if($query->rowCount() == 0) {
            return false;
        }else {
            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["id"]);
        }
        
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

    public function get_full_name() : String {
        return $this->full_name;
        
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


    public static function validate($mail) : array {
        $errors = [];
        if (!strlen($mail) > 0) {
            $errors[] = "Mail is requiered.";
        } if (!(strlen(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $mail)))){
            $errors[] = "Email must have a valid structure.";
        }
        return $errors;
    }

    private static function check_password($clear_password, string $hash) : bool {
        return $hash === Tools::my_hash($clear_password);
    }

    public static function validate_login(string $mail, string $password) : array {
        $errors = [];
        $user = User::get_user_by_mail($mail);
        if($user) {
            if(!self::check_password($password, $user->hashed_password)) {
                $errors[] = "Wrong password.please try again.";
            }
        }else {
            $errors[] = "Can't find the user. Please sign up.";
        }
        return $errors;
    }
    private static function validate_password(string $password) : array {
        $errors = [];
        if (strlen($password) < 8) {
            $errors[] = "Password length must be up 8 char.";
        } if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?!\\-]/", $password))) {
            $errors[] = "Password must contain one uppercase letter, one number and punctuation mark.";
        }
        return $errors;

    }
    public static function validate_passwords(string $password, string $password_confirm) : array {
        $errors = User::validate_password($password);
        if ($password != $password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }
    public static function validate_unicity(string $mail) : array {
        $errors = [];
        $user = self::get_user_by_mail($mail);
        if ($user) {
            $errors[] = "This user already exists.";
        }
        return $errors;
    }

    public function get_notes() : array {
        return Note::get_notes($this);
    }

    
  /*  public static function get_archives(int $owner) : array{
        $archives = [];
        $query = self::execute("select id, title from notes where owner = :ownerid and archived = 1", ["ownerid" => $owner]);
        $archives = $query->fetchAll();
        foreach($archives as $row) {
            $data = self::execute("select content from text_notes where id = :row", ["row" => $row["id"]]);

        }
        return array_($archives, $data->fetchAll());

    }
*/

    public function get_archives(): array {
        $archives = [];
        $query = self::execute("SELECT id, title FROM notes WHERE owner = :ownerid AND archived = 1 ORDER BY -weight" , ["ownerid" => $this->id]);
        $archives = $query->fetchAll();
    
        foreach ($archives as &$row) {
            $dataQuery = self::execute("SELECT content FROM text_notes WHERE id = :note_id", ["note_id" => $row["id"]]);
            $content = $dataQuery->fetchColumn(); 
            if($content === null) {
                $dataQuery = self::execute("SELECT content FROM checklist_note_items WHERE id = :note_id ", ["note_id" => $row["checklist_note"]]);
                $checklist_content = $dataQuery->fetchAll();
                $content = $dataQuery->fetchColumn();
            }
            $row["content"] = $content;
        }
        return $archives;
    }
     
    public function get_shared_note(): array {
        $shared = [];
        $query = self::execute("SELECT note from note_shares WHERE user = :userid" , ['userid'=>$this->id]);
        $shared_note_id = $query->fetchAll(PDO::FETCH_COLUMN);
        foreach ($shared_note_id as $note_id) {
           $note = Note::get_note($note_id);
            $shared[] = $note;

        }
        return $shared;
    }
 
}