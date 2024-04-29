<?php
require_once "framework/Model.php";
require_once "User.php";
require_once "Note.php";

class NoteShare extends Model
{
    public function __construct(public int $note, public int $user, public bool $editor)
    {
    }

    public static function get_shared_by(int $userid, int $ownerid): array
    {
        $shared_by = [];
        $query = self::execute("SELECT id, title, editor FROM notes JOIN note_shares ON notes.id = note_shares.note WHERE note_shares.user = :userid and 
        notes.owner = :ownerid", ["ownerid" => $ownerid, "userid" => $userid]);
        $shared_by = $query->fetchAll();
        $content_checklist = [];
        foreach ($shared_by as &$row) {
            $dataQuery = self::execute("SELECT content FROM text_notes WHERE id = :note_id", ["note_id" => $row["id"]]);
            $content = $dataQuery->fetchColumn();

            if (!$content) {
                $dataQuery = self::execute("SELECT content, checked FROM checklist_note_items WHERE checklist_note = :note_id ", ["note_id" => $row["id"]]);
                $content_checklist = $dataQuery->fetchAll();
            }
            $row["content"] = $content;
            $row["content_checklist"] = $content_checklist;
        }
        return $shared_by;
    }


    public static function get_shared_note(User $user): array
    {
        $shared = [];
        $query = self::execute("SELECT note from note_shares WHERE user = :userid", ['userid' => $user->id]);
        $shared_note_id = $query->fetchAll(PDO::FETCH_COLUMN);
        foreach ($shared_note_id as $note_id) {
            $note = Note::get_note_by_id($note_id);

            $shared[] = $note;
        }
        return $shared;
    }

    public static function get_shared_users(Note $note): array
    {
        $query = self::execute("SELECT user, editor FROM note_shares WHERE note = :note_id", ["note_id" => $note->note_id]);
        $data = $query->fetchAll();
        $shared_users = [];
        foreach ($data as $row) {
            $user = User::get_user_by_id($row['user']);
            //vérifier que l'user existe
            if ($user) {
                $shared_users[] = array($row['user'], $user->full_name, $row['editor']);
            }
        }
        return $shared_users;
    }
    public function persist(): NoteShare|array
    {
        if ($this->note == null || $this->user == null) {
            return []; // Si la note ou l'utilisateur est manquant, retourner un tableau vide
        }

        // Vérification si une entrée avec la même note et le même utilisateur existe déjà
        $query = self::execute("SELECT COUNT(*) FROM note_shares WHERE note = :note_id AND user = :user_id", [
            "note_id" => $this->note,
            "user_id" => $this->user
        ]);
        $existing_count = $query->fetchColumn();

        // Si une entrée existe déjà, effectuer une mise à jour
        if ($existing_count > 0) {
            self::execute("UPDATE note_shares SET editor = :editor WHERE note = :note_id AND user = :user_id", [
                "editor" => $this->editor ? 1 : 0,
                "note_id" => $this->note,
                "user_id" => $this->user
            ]);
        } else {
            // Sinon, effectuer une insertion
            self::execute("INSERT INTO note_shares (note, user, editor) VALUES (:note_id, :user_id, :editor)", [
                "note_id" => $this->note,
                "user_id" => $this->user,
                "editor" => $this->editor ? 1 : 0
            ]);
        }

        return $this;
    }

    public function delete()
    {
        $query = self::execute("DELETE FROM note_shares WHERE user = :userid AND note = :note_id", ['userid' => $this->user, "note_id" => $this->note]);
        return $query->rowCount() > 0;
    }

    public static function get_share_note(int $note_id, int $user_id): NoteShare | bool
    {
        $query = self::execute("SELECT * from note_shares WHERE user = :userid AND note = :note_id", ['userid' => $user_id, "note_id" => $note_id]);
        $data = $query->fetchAll();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $ns = new NoteShare($row['note'], $row['user'], $row['editor']);
            }
            return $ns;
        }
        return false;
    }
}
