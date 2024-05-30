<?php
require_once "Note.php";
require_once "CheckListNote.php";
require_once "CheklistNoteItem.php";

class CheckListNoteItem extends Model
{
    public function __construct(
        public int $id,
        public int $checklist_note,
        public string $content,
        public int $checked
    ) {
    }
    public function validate_item()
    {
        $errors = [];
        $minLength = Configuration::get('item_min_length');
        $maxLength = Configuration::get('item_max_length');

        // Vérifie la longueur du titre
        if (strlen($this->content) < $minLength || strlen($this->content) > $maxLength) {
            $errors[] = "L'item doit avoir au minimum $minLength caractères et au maximum $maxLength caractères.";
        }


        return $errors;
    }
    public static function update_checked(int $checklist_item_id, bool $checked): bool
    {
        self::execute("UPDATE checklist_note_items SET checked =:checked WHERE id = :id", ["id" => $checklist_item_id, "checked" => $checked]);
        return true;
    }
    public static function get_checklist_note(int $checklist_item_id): int
    {
        $query = self::execute("SELECT checklist_note FROM checklist_note_items WHERE id = :id", ["id" => $checklist_item_id]);
        $data = $query->fetchColumn();
        return $data;
    }

    public function persist(): CheckListNoteItem 
    {
            // Vérifier si l'item existe
            $existingItem = self::get_item_by_id($this->id);
            if ($existingItem) {
                // Mettre à jour l'item existant
                self::execute(
                    "UPDATE checklist_note_items SET checklist_note = :checklist_note, content = :content, checked = :checked WHERE id = :id",
                    [
                        "id" => $this->id,
                        "checklist_note" => $this->checklist_note,
                        "content" => $this->content,
                        "checked" => $this->checked
                    ]
                );
                return $this;
            } else {
                
                // Créer un nouvel item si l'item n'existe pas
                self::execute(
                    "INSERT INTO checklist_note_items (checklist_note, content, checked) VALUES (:checklist_note, :content, :checked)",
                    [
                        "checklist_note" => $this->checklist_note,
                        "content" => $this->content,
                        "checked" => $this->checked
                    ]
                );
                $this->id = self::lastInsertId();
                return $this;
            }
    

    }

    public function is_unique() : bool {
        $item = $this->get_item_by_id($this->id);
        if ($item) {
            return false;
        } else {
            return true;
        }
    }

    public function delete(): void
    {
        self::execute("DELETE FROM checklist_note_items WHERE id = :id", ["id" => $this->id]);
    }

    public static function get_item_by_id(int $item_id): ?CheckListNoteItem
    {
        $query = self::execute("SELECT * FROM checklist_note_items WHERE id = :id", ["id" => $item_id]);
        $data = $query->fetch();
        return $data ? new CheckListNoteItem($data['id'], $data['checklist_note'], $data['content'], $data['checked']) : null;
    }
}
