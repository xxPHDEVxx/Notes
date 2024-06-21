<?php
// Inclusion des fichiers nécessaires
require_once "framework/Model.php"; // Classe de base pour les modèles
require_once "User.php"; // Classe pour la gestion des utilisateurs
require_once "ChecklistNoteItem.php"; // Classe pour la gestion des éléments de checklist

// Définition de la classe ChecklistNote, héritant de la classe Note
class ChecklistNote extends Note
{

    // Méthode pour obtenir le contenu de la checklist
    public function get_content(): array|null
    {
        // Exécute une requête pour récupérer les éléments de la checklist associés à cette note
        $query = self::execute("SELECT * FROM checklist_note_items 
            WHERE checklist_note = :id ORDER BY checked, id", ["id" => $this->note_id]);
        // Récupère les données sous forme de tableau
        $data = $query->fetchAll();
        return $data; // Retourne les éléments de la checklist
    }

    // Méthode pour définir le contenu de la checklist (non implémentée)
    public function set_content($data)
    {
    }

    // Méthode pour obtenir le type de la note
    public function get_type(): string
    {
        return TypeNote::CLN; // Retourne le type spécifique pour les checklists
    }

    // Méthode pour obtenir une note spécifique (type checklist) par ID
    public function get_note(): Note|false
    {
        // Exécute une requête pour récupérer la note et ses détails
        $query = self::execute("SELECT * FROM Notes JOIN checklist_notes ON notes.id = checklist_notes.id 
            WHERE notes.id = :id", ["id" => $this->note_id]);
        $data = $query->fetch(); // Récupère les données de la note

        if ($query->rowCount() == 0) {
            return false; // Retourne false si aucune note n'est trouvée
        } else {
            // Crée et retourne un nouvel objet ChecklistNote avec les données récupérées
            return new CheckListNote(
                $data['id'],
                $data['title'],
                $data['owner'],
                $data['created_at'],
                $data['pinned'],
                $data['archived'],
                $data['weight'],
                $data['edited_at']
            );
        }
    }

    // Méthode pour obtenir une note spécifique (type checklist) par ID
    public static function get_note_by_id(int $note_id): Note|false
    {
        // Exécute une requête pour récupérer la note avec l'ID fourni
        $query = self::execute("SELECT * FROM notes WHERE id = :id", ["id" => $note_id]);
        $data = $query->fetch(); // Récupère les données de la note

        if (count($data) !== 0) {
            // Crée et retourne un nouvel objet ChecklistNote avec les données récupérées
            return new CheckListNote(
                $data['id'],
                $data['title'],
                $data['owner'],
                $data['created_at'],
                $data['pinned'],
                $data['archived'],
                $data['weight'],
                $data['edited_at']
            );
        }
        return false; // Retourne false si aucune note n'est trouvée
    }

    // Méthode pour vérifier si la note est épinglée
    public function isPinned(): bool
    {
        return $this->pinned; // Retourne l'état d'épingle de la note
    }

    // Méthode pour mettre à jour la checklist (non implémentée)
    public function update()
    {
    }

    // Méthode pour créer une nouvelle entrée de checklist
    public function new(): void
    {
        // Exécute une requête pour insérer une nouvelle checklist dans la base de données
        self::execute("INSERT INTO `checklist_notes`(`id`) VALUES (:id)", ["id" => $this->note_id]);
    }
}
