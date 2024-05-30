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
            $errors[] = "Le label doit contenir entre $minLength et $maxLength caractères.";
        }

        // Vérifie si un autre label avec le même contenu existe déjà dans la base de données
        $data_sql = self::execute("SELECT COUNT(*) FROM note_labels WHERE label = :label AND note = :note", [
            'label' => $this->label,
            'note' => $this->note
        ]);
        if ($data_sql->fetchColumn() > 0) {
            $errors[] = "Un autre label avec le même contenu existe déjà.";
        }

        // Vérifie si le label contient un espace
        if (strpos($this->label, ' ') !== false) {
            $errors[] = "Le label ne peut pas contenir d'espace";
        }

        return $errors;
    }


}


