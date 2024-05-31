<?php

require_once "framework/Model.php";

class NoteLabel extends Model
{
    public function __construct(
        public int $note,
        public string $label,
    ) {
    }

    public function getNote(): int
    {
        return $this->note;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function validate_label()
    {
        $errors = [];

        // Récupère les longueurs minimale et maximale du label depuis la configuration
        $minLength = Configuration::get('label_min_length');
        $maxLength = Configuration::get('label_max_length');

        // Vérifie si la longueur du label est comprise entre la longueur minimale et maximale
        if (strlen($this->label) < $minLength || strlen($this->label) > $maxLength) {
            $errors[] = "Label lenght must be between $minLength and $maxLength.";
        }

        // Vérifie si un autre label avec le même contenu existe déjà dans la base de données
        $data_sql = self::execute("SELECT COUNT(*) FROM note_labels WHERE label = :label AND note = :note", [
            'label' => $this->label,
            'note' => $this->note
        ]);
        if ($data_sql->fetchColumn() > 0) {
            $errors[] = "A note cannot contain the same label twice.";
        }

        // Vérifie si le label contient un espace
        if (strpos($this->label, ' ') !== false) {
            $errors[] = "Le label ne peut pas contenir d'espace";
        }

        return $errors;
    }

    public function persist()
    {
        self::execute(
            "INSERT INTO note_labels (note, label) VALUES (:note,:label)",
            [
                "note" => $this->note,
                "label" => $this->label
            ]
        );
    }

    public static function get_note_label(int $note, string $label)
    {
        $query = self::execute(
            "SELECT * FROM note_labels WHERE note = :note AND label = :label",
            [
                "note" => $note,
                "label"  => $label
            ]
        );
        $data = $query->fetch();
        if (count($data) !== 0) {
            return new NoteLabel($data["note"], $data["label"]);
        }
    }

    public function delete()
    {
        self::execute(
            "DELETE FROM note_labels WHERE note = :note AND label = :label",
            [
                "note" => $this->note,
                "label" => $this->label
            ]
        );
    }

    public static function get_labels(User $user)
    {

        $query = self::execute("SELECT DISTINCT nl.label
            FROM note_labels nl
            INNER JOIN notes n ON nl.note = n.id
            WHERE n.owner = :owner", 
            ["owner" => $user->id]);
        $labels = [];
        $data = $query->fetchAll();
        foreach ($data as $row) {
            $labels[] = $row["label"];
        }
        return $labels;
    }
}
