<?php
require_once "framework/Model.php";
require_once "Note.php";
require_once "NoteShare.php";
require_once "Note.php";

class User extends Model
{

    public function __construct(public string $mail, public string $hashed_password, public string $full_name, public ?string $role = "user", public ?int $id = NULL)
    {
    }



    public static function get_user_by_mail(string $mail): User|false
    {
        $query = self::execute("SELECT * FROM Users where mail = :mail", ["mail" => $mail]);
        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User(
                $data["mail"],
                $data["hashed_password"],
                $data["full_name"],
                $data["role"],
                $data["id"]
            );
        }
    }
    public static function get_user_by_id(int $user_id): User|false
    {
        $query = self::execute("SELECT * FROM Users where id = :id", ["id" => $user_id]);

        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["mail"], $data["hashed_password"], $data["full_name"], $data["role"], $data["id"]);
        }
    }
    
    public function get_max_weight(){
        return Note::get_max_weight($this);
    }


    public function persist(): User
    {
        if (self::get_user_by_mail($this->mail)) {

            self::execute(
                "UPDATE Users SET mail=:mail, hashed_password=:hashed_password, full_name=:full_name, role=:role WHERE id=:id ",
                ["mail" => $this->mail, "hashed_password" => $this->hashed_password, "full_name" => $this->full_name, "role" => $this->role]
            );
        } else {
            self::execute(
                "INSERT INTO Users(mail,hashed_password,full_name,role) VALUES (:mail,:hashed_password,:full_name,:role)",
                ["mail" => $this->mail, "hashed_password" => $this->hashed_password, "full_name" => $this->full_name, "role" => $this->role]
            );
            $user = self::get_user_by_id(self::lastInsertId());
            $this->id = $user->id;
        }
        return $this;
    }



    public static function get_users(): array
    {
        $query = self::execute("SELECT * FROM users ORDER BY full_name" , []);
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new User(
                $row["mail"],
                $row["hashed_password"],
                $row["full_name"],
                $row["role"],
                $row["id"]
            );
        }
        return $results;
    }


    public function validate(): array
    {

        $errors = [];
        if (!strlen($this->mail) > 0) {
            $errors[] = "⚠Mail is requiered.";
        }
        if (!(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $this->mail))) {
            $errors[] = "⚠Email must have a valid structure.";
        }
        return $errors;
    }

    public static function validate_edit($email, $fullname, $currentUser): array
    {
        $errors = [];
        if (!strlen($email) > 0) {
            $errors[] = "⚠Mail is required.";
        }
        if (!(preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email))) {
            $errors[] = "⚠Email must have a valid structure.";
        }
        if (!strlen($fullname) > 0) {
            $errors[] = "⚠Full Name is required.";
        }
        if (!(strlen($fullname) >= 3)) {
            $errors[] = "⚠Full Name must have more than 3 characters.";
        }

    $currentEmail = $currentUser ? $currentUser->mail : null;
    $currentUserId = $currentUser ? $currentUser->id : null;

    // Check if email field has been touched
    if ($email !== $currentEmail) {
        if ($currentEmail !== null) {
            $existingUser = User::get_user_by_mail($email);
            if ($existingUser && $existingUser->id != $currentUserId) {
                $errors[] = "⚠This email is already used by another user.";
            }
        }
    }
    return $errors;
}

    private static function check_password($clear_password, string $hash): bool
    {
        return $hash === Tools::my_hash($clear_password);
    }

    public static function validate_login(string $mail, string $password): array
    {
        $errors = [];
        $user = User::get_user_by_mail($mail);
        if ($user) {
            if (!self::check_password($password, $user->hashed_password)) {
                $errors[] = "⚠Wrong password. Please try again.";
            }
        } else {
            $errors[] = "⚠Can't find the user. Please sign up.";
        }
        return $errors;
    }
    private static function validate_password(string $password, $currentUser): array
    {
        $errors = [];
        if (strlen($password) < 8) {
            $errors[] = "⚠Password length must be up 8 char.";
        }
        if (!((preg_match("/[A-Z]/", $password)) && preg_match("/\d/", $password) && preg_match("/['\";:,.\/?!\\-]/", $password))) {
            $errors[] = "⚠Password must contain one uppercase letter, one number and punctuation mark.";
        }

        // Récupérer le mot de passe actuel de l'utilisateur
        if ($currentUser && Tools::my_hash($password) === $currentUser->getHashedPassword()) {
            $errors[] = "⚠The new password cannot be the same as the current password.";
        }
        return $errors;
    }
    public static function validate_passwords(string $password, string $password_confirm, $currentPassword): array
    {
        $errors = User::validate_password($password, $currentPassword);
        if ($password != $password_confirm) {
            $errors[] = "⚠You have to enter twice the same password.";
        }
        return $errors;
    }
    public static function validate_unicity(string $mail): array
    {
        $errors = [];
        $user = self::get_user_by_mail($mail);
        if ($user) {
            $errors[] = "⚠This user already exists.";
        }
        return $errors;
    }

    public function validate_name(): array
    {
        $errors = [];
        if (!strlen($this->full_name) > 0) {
            $errors[] = "⚠Full Name is required.";
        }
        if (!(strlen($this->full_name) >= 3)) {
            $errors[] = "⚠Full Name must have mutch than 3 char";
        }
        return $errors;
    }




    public function get_archives(): array
    {
        return Note::get_archives($this);
    }

    public function get_shared_by(int $ownerid): array
    {
        return NoteShare::get_shared_by($this->id, $ownerid);
    }


    public function shared_by(): array
    {

        $shared =  NoteShare::get_shared_note($this);
        $ids = [];
        foreach ($shared as $shared_note) {
            $id = $shared_note->owner;
            $ids[] = $id;
        }
        $idsUnique = array_unique($ids);
        $sharers = [];
        foreach ($idsUnique as $userid) {
            $user = User::get_user_by_id($userid);
            $sharers[] = $user;
        }
        return $sharers;
    }
    public function get_notes_pinned(): array
    {
        return Note::get_notes_pinned($this);
    }
    public function get_notes_unpinned(): array
    {
        return Note::get_notes_unpinned($this);
    }
    public function get_notes_search($labels){
        return Note::get_notes_search($this, $labels);
    }


    public function update_profile(string $newFullName, string $newMail): void
    {
        $this->mail = $newMail;
        $this->full_name = $newFullName;

        $sql = "UPDATE users SET full_name = :full_name, mail = :mail WHERE id = :id";
        $params = [':full_name' => $newFullName, ':mail' => $newMail, ':id' => $this->id];

        try {
            $stmt = parent::execute($sql, $params);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour du profil : " . $e->getMessage());
        }
    }

    public function setPassword($newPassword)
    {
        $hashedPassword = Tools::my_hash($newPassword);
        $this->hashed_password = $hashedPassword;
    }

    public function getHashedPassword()
    {
        return $this->hashed_password;
    }

    public function updatePassword($newPassword)
    {

        $this->setPassword($newPassword);

        $sql = "UPDATE users SET hashed_password = :hashed_password WHERE id = :id";
        $params = array(':hashed_password' => $this->getHashedPassword(), ':id' => $this->id);

        try {
            $stmt = parent::execute($sql, $params);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour du mot de passe : " . $e->getMessage());
        }
    }
    public function get_labels(): array {
        return NoteLabel::get_labels($this);
    }
}
