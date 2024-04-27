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
                $shared_users[] = array($row['user'],$user->full_name, $row['editor']);
            }
        }
        return $shared_users;
    }
}
