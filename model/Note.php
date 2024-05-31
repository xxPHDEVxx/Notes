<?php

require_once "framework/Model.php";
require_once "User.php";
require_once "TextNote.php";
require_once "ChecklistNote.php";
require_once "framework/Configuration.php";

enum TypeNote
{
    const TN = "TextNote";
    const CLN = "ChecklistNote";
}


abstract class Note extends Model
{
    public function __construct(
        public int $note_id,
        public string $title,
        public int $owner,
        public string $created_at,
        public int $pinned,
        public int $archived,
        public int $weight,
        public ?string $edited_at = NULL
    ) {
    }

    public abstract function get_type();
    public abstract function get_content();
    public abstract function set_content(?string $data);
    public abstract function get_note();
    public abstract function isPinned();
    public abstract function update();



    public function get_id(): int
    {
        return 5;
    }

    /**
     * Récupère les libellés associés à la note.
     *
     * @return array Les libellés associés à la note.
     */
    public function get_labels()
    {
        // Initialise un tableau vide pour stocker les libellés
        $labels = [];

        // Exécute une requête SQL pour récupérer les libellés de la base de données
        $data_sql = self::execute("SELECT label FROM note_labels WHERE note = :id", ["id" => $this->note_id]);

        // Récupère les résultats de la requête sous forme de tableau de colonnes
        // Utilise FETCH_COLUMN pour obtenir uniquement la première colonne des résultats
        // (dans ce cas, la colonne contenant les libellés)
        $labels = $data_sql->fetchAll(PDO::FETCH_COLUMN, 0);

        // Retourne le tableau des libellés
        return $labels;
    }


    public static function get_created_at(int $id): string
    {
        $query = self::execute("SELECT created_at from notes WHERE id = :id", ["id" => $id]);
        $data = $query->fetchColumn();

        return $data;
    }
    public static function get_edited_at(int $id): string|null
    {
        $query = self::execute("SELECT edited_at from notes WHERE id = :id", ["id" => $id]);
        $data = $query->fetchColumn();

        return $data;
    }
    public function is_shared_as_editor(int $userid): bool
    {
        $query = self::execute("SELECT * FROM note_shares WHERE note = :id and user =:userid and editor = 1", ["id" => $this->note_id, "userid" => $userid]);
        $data = $query->fetchAll();
        return count($data) !== 0;
    }
    public function is_shared_as_reader(int $userid): bool
    {
        $query = self::execute("SELECT * FROM note_shares WHERE note = :id and user =:userid and editor = 0", ["id" => $this->note_id, "userid" => $userid]);
        $data = $query->fetchAll();
        return count($data) !== 0;
    }
    public function in_my_archives(int $userid): int
    {
        $query = self::execute("SELECT archived FROM notes WHERE owner = :userid and id = :id", ["userid" => $userid, "id" => $this->note_id]);
        $data = $query->fetchColumn();
        return $data;
    }
    public function is_pinned(int $userid): int
    {
        $query = self::execute("SELECT pinned FROM notes WHERE owner = :userid and id = :id", ["userid" => $userid, "id" => $this->note_id]);
        $data = $query->fetchColumn();
        return $data;
    }


    public static function get_archives(User $user): array
    {
        $archives = [];
        $query = self::execute("SELECT id, title FROM notes WHERE owner = :ownerid AND archived = 1 ORDER BY -weight", ["ownerid" => $user->id]);
        $archives = $query->fetchAll();
        $content_checklist = [];
        foreach ($archives as &$row) {
            $dataQuery = self::execute("SELECT content FROM text_notes WHERE id = :note_id", ["note_id" => $row["id"]]);
            $content = $dataQuery->fetchColumn();

            if (!$content) {
                $dataQuery = self::execute("SELECT content, checked FROM checklist_note_items WHERE checklist_note = :note_id order by checked, id ", ["note_id" => $row["id"]]);
                $content_checklist = $dataQuery->fetchAll();
            }
            $row["content"] = $content;
            $row["content_checklist"] = $content_checklist;
        }
        return $archives;
    }
    public function archive(): void
    {
        self::execute("UPDATE notes SET archived = :val WHERE id = :id", ["val" => 1, "id" => $this->note_id]);
        $this->order_notes();
    }

    public function unarchive(): void
    {
        self::execute("UPDATE notes SET archived = :val WHERE id = :id", ["val" => 0, "id" => $this->note_id]);
        $this->order_notes();
    }
    public function pin(): void
    {
        self::execute("UPDATE notes SET pinned = :val WHERE id = :id", ["val" => 1, "id" => $this->note_id]);
        $this->order_notes();
    }
    public function unpin(): void
    {
        if ($this->is_pinned($this->owner) == 1) {
            self::execute("UPDATE notes SET pinned = :val WHERE id = :id", ["val" => 0, "id" => $this->note_id]);
            $this->order_notes();
        }
    }

    // récupérer nombre total de notes d'un user spécifique
    public function get_all_notes_by_user($user)
    {
        $dataSql = self::execute("SELECT id FROM notes WHERE owner = :user", ["user" => $user]);
        $data = $dataSql->fetchAll(PDO::FETCH_COLUMN, 0);
        return $data;
    }

    // Donne poids négatif unique aux notes
    public function temporary_weights($array_id)
    {
        $notes = $array_id;
        $nb = count($this->get_all_notes_by_user($this->owner)) + 50;// 50 pour éviter conflit de poids lors de plusieurs supressions
        foreach ($notes as $note) {
            self::execute("UPDATE notes SET weight = :val WHERE id = :id", ["val" => ++$nb, "id" => $note]);
        }
    }

    // Fonction pour trier les poids en ordre décroissant
    function sort_weights_desc($weights)
    {
        $n = count($weights);
        // Boucle externe pour chaque élément du tableau
        for ($i = 0; $i < $n - 1; $i++) {
            // Boucle interne pour les comparaisons successives
            for ($j = 0; $j < $n - $i - 1; $j++) {
                // Comparaison et échange si nécessaire
                if ($weights[$j] < $weights[$j + 1]) {
                    $stack = $weights[$j];
                    $weights[$j] = $weights[$j + 1];
                    $weights[$j + 1] = $stack;
                }
            }
        }
        return $weights;
    }

    public function new_order($new_order)
    {
        $i = 0;
        foreach ($new_order as $id) {
            $dataSql = self::execute("SELECT weight FROM notes WHERE id = :id", ["id" => $id]);
            $weights[$i] = $dataSql->fetchColumn();
            $i++;
        }
        $new_weights = $this->sort_weights_desc($weights);
        $this->temporary_weights($new_order);

        $i = 0;
        foreach ($new_order as $id) {
            self::execute("UPDATE notes SET weight = :val WHERE id = :id", ["id" => $id, "val" => $new_weights[$i]]);
            $i++;
        }

    }

    // Ordonnes les notes selon les règles métiers liées au poids (owner -> pin -> unpin -> ...) à factoriser 
    public function order_notes()
    {
        // Identifiant de l'utilisateur
        $user_id = $this->owner;
        // Récupérer toutes les notes de l'utilisateur
        $notes = $this->get_all_notes_by_user($user_id);
        // Appliquer des poids temporaires aux notes
        $this->temporary_weights($notes);
        // Initialiser le compteur de poids
        $nb = count($notes);

        // Récupération des notes épinglées de l'utilisateur
        if ($this->is_pinned($user_id) == 1) {
            // Si la note actuelle est épinglée, la placer en tête de liste
            self::execute("UPDATE notes SET weight = :val WHERE id = :id", ["val" => $nb--, "id" => $this->note_id]);
            // Exclure la note actuelle de la liste des notes épinglées
            $dataSql = self::execute("SELECT id FROM notes WHERE pinned = :val AND owner = :user AND id != :note_id ORDER BY weight DESC", ["val" => 1, "user" => $user_id, "note_id" => $this->note_id]);
        } else {
            // Récupérer toutes les notes épinglées de l'utilisateur
            $dataSql = self::execute("SELECT id FROM notes WHERE pinned = :val AND owner = :user ORDER BY weight DESC", ["val" => 1, "user" => $user_id]);
        }
        // Récupérer les notes épinglées
        $pinned = $dataSql->fetchAll(PDO::FETCH_COLUMN, 0);

        // Numéroter les notes épinglées (en commençant par le plus grand poids)
        foreach ($pinned as $note) {
            self::execute("UPDATE notes SET weight = :val WHERE id = :id", ["val" => $nb--, "id" => $note]);
        }

        // Récupération des notes non épinglées de l'utilisateur
        if ($this->is_pinned($user_id) == 0) {
            // Si la note actuelle n'est pas épinglée, la placer en tête de liste
            self::execute("UPDATE notes SET weight = :val WHERE id = :id", ["val" => $nb--, "id" => $this->note_id]);
            // Exclure la note actuelle de la liste des notes non épinglées
            $dataSql = self::execute("SELECT id FROM notes WHERE pinned = :val AND owner = :user AND id != :note_id ORDER BY weight DESC", ["val" => 0, "user" => $user_id, "note_id" => $this->note_id]);
        } else {
            // Récupérer toutes les notes non épinglées de l'utilisateur
            $dataSql = self::execute("SELECT id FROM notes WHERE pinned = :val AND owner = :user ORDER BY weight DESC", ["val" => 0, "user" => $user_id]);
        }
        // Récupérer les notes non épinglées
        $unpinned = $dataSql->fetchAll(PDO::FETCH_COLUMN, 0);

        // Numéroter les notes non épinglées (en commençant par le plus grand poids)
        foreach ($unpinned as $note) {
            self::execute("UPDATE notes SET weight = :val WHERE id = :id", ["val" => $nb--, "id" => $note]);
        }
    }

    public function get_shared_users()
    {
        return NoteShare::get_shared_users($this);
    }



    public function get_weight(): int
    {
        return $this->weight;
    }

    public function set_weight(int $weight): void
    {
        $this->weight = $weight;
    }

    private static function get_notes(User $user, bool $pinned): array
    {
        $pinnedCondition = $pinned ? '1' : '0';

        $notes = [];
        $query = self::execute("SELECT * FROM notes WHERE owner = :ownerid AND archived = 0 AND pinned = :pinned ORDER BY -weight", ["ownerid" => $user->id, "pinned" => $pinnedCondition]);
        $notes = $query->fetchAll();
        $content_checklist = [];
        foreach ($notes as &$row) {
            $dataQuery = self::execute("SELECT content FROM text_notes WHERE id = :note_id", ["note_id" => $row["id"]]);
            $content = $dataQuery->fetchColumn();

            if (!$content) {
                $dataQuery = self::execute("SELECT content, checked FROM checklist_note_items WHERE checklist_note = :note_id order by checked, id ", ["note_id" => $row["id"]]);
                $content_checklist = $dataQuery->fetchAll();
            }
            $row["content"] = $content;
            $row["content_checklist"] = $content_checklist;
        }

        return $notes;
    }

    public static function get_max_weight(User $user)
    {
        $query = self::execute("SELECT MAX(weight) FROM notes WHERE owner = :user", ["user" => $user->id]);
        $data = $query->fetchColumn();
        return $data + 1;
    }

    public function get_max_pinned_weight(User $user)
    {
        $query = self::execute("SELECT MAX(weight) FROM notes WHERE owner = :user AND pinned = val", ["val" => 1, "user" => $user->id]);
        $data = $query->fetchColumn();
        return $data + 1;
    }

    public function get_max_unpinned_weight(User $user)
    {
        $query = self::execute("SELECT MAX(weight) FROM notes WHERE owner = :user AND pinned = val", ["val" => 0, "user" => $user->id]);
        $data = $query->fetchColumn();
        return $data + 1;
    }


    public static function get_notes_pinned(User $user): array
    {
        return self::get_notes($user, true);
    }

    public static function get_notes_unpinned(User $user): array
    {
        return self::get_notes($user, false);
    }


    // Supprime une note
    public function delete(User $initiator): Note|false
    {
        $user = User::get_user_by_id($this->owner);
        // permet la suppression en cascade pour éviter problèmes suite aux dépendances
        if ($user == $initiator) {
            self::execute("DELETE FROM note_labels WHERE note = :note_id", ['note_id' => $this->note_id]);
            self::execute("DELETE FROM checklist_note_items WHERE checklist_note = :note_id", ['note_id' => $this->note_id]);
            self::execute("DELETE FROM text_notes WHERE id = :note_id", ['note_id' => $this->note_id]);
            self::execute("DELETE FROM checklist_notes WHERE id = :note_id", ['note_id' => $this->note_id]);
            self::execute("DELETE FROM note_shares WHERE note = :note_id", ['note_id' => $this->note_id]);
            self::execute("DELETE FROM Notes WHERE id = :note_id", ['note_id' => $this->note_id]);
            return $this;
        }
        return false;
    }

    public function validate(): array
    {
        $errors = [];

        $minLength = Configuration::get('title_min_length');
        $maxLength = Configuration::get('title_max_length');
        if (strlen($this->title) < $minLength || strlen($this->title) > $maxLength) {
            $errors[] = "Le titre doit contenir entre $minLength et $maxLength caractères.";
        }


        return $errors;
    }
    public function validate_title()
    {
        $errors = [];
        $minLength = Configuration::get('title_min_length');
        $maxLength = Configuration::get('title_max_length');

        // Vérifie la longueur du titre
        if (strlen($this->title) < $minLength || strlen($this->title) > $maxLength) {
            $errors[] = "Le titre doit avoir au minimum 3 caractères et au maximum 25 caractères.";
        }

        // Vérifie si le titre est unique pour cet utilisateur
        $query = self::execute("SELECT COUNT(*) FROM notes WHERE title = :title AND owner = :owner AND id != :id", [
            'title' => $this->title,
            'owner' => $this->owner,
            'id' => $this->note_id ?? 0
        ]);
        if ($query->fetchColumn() > 0) {
            $errors[] = "Une autre note avec le même titre existe déjà.";
        }

        return $errors;
    }

    public function validate_title_service($title)
    {
        $errors = [];
        $minLength = Configuration::get('title_min_length');
        $maxLength = Configuration::get('title_max_length');

        // Vérifie la longueur du titre
        if (strlen($title) < $minLength) {
            $errors[] = "Le titre doit contenir au minimum 3 caractères";
        }

        if (strlen($title) > $maxLength) {
            $errors[] = "Le titre doit contenir au maximum 25 caractères.";
        }

        // Vérifie si le titre est unique pour cet utilisateur
        $query = self::execute("SELECT COUNT(*) FROM notes WHERE title = :title AND owner = :owner AND id != :id", [
            'title' => $title,
            'owner' => $this->owner,
            'id' => $this->note_id ?? 0
        ]);
        if ($query->fetchColumn() > 0) {
            $errors[] = "Une autre note avec le même titre existe déjà.";
        }

        return $errors;
    }

    public static function validate_new_title_service($title)
    {
        $errors = [];
        $minLength = Configuration::get('title_min_length');
        $maxLength = Configuration::get('title_max_length');

        // Vérifie la longueur du titre
        if (strlen($title) < $minLength) {
            $errors[] = "Le titre doit contenir au minimum 3 caractères";
        }

        if (strlen($title) > $maxLength) {
            $errors[] = "Le titre doit contenir au maximum 25 caractères.";
        }

        // Vérifie si le titre est unique pour cet utilisateur
        $query = self::execute("SELECT COUNT(*) FROM notes WHERE title = :title", [
            'title' => $title,
        ]);
        if ($query->fetchColumn() > 0) {
            $errors[] = "Une autre note avec le même titre existe déjà.";
        }

        return $errors;
    }

    public function validate_content()
    {
        $minLength = Configuration::get('description_min_length');
        $maxLength = Configuration::get('description_max_length');
        $errors = [];
        $contentLength = strlen($this->get_content());

        // Vérifie que le contenu est soit vide, soit entre minLength et maxLength caractères
        if (($contentLength > 0 && $contentLength < $minLength) || $contentLength > $maxLength) {
            $errors[] = "Le contenu de la note doit contenir entre 5 et 800 caractères ou être vide.";
        }

        return $errors;
    }

    public static function validate_content_service($content)
    {
        $minLength = Configuration::get('description_min_length');
        $maxLength = Configuration::get('description_max_length');
        $errors = [];
        $contentLength = strlen($content);

        // Vérifie que le contenu est soit vide, soit entre minLength et maxLength caractères
        if (($contentLength > 0 && $contentLength < $minLength) || $contentLength > $maxLength) {
            $errors[] = "Le contenu de la note doit contenir 5 et 800 caractères ou être vide.";
        }

        return $errors;
    }




    public function persist(): Note|array
    {
        if ($this->note_id == null) {
            $errors = $this->validate();
            if (empty($errors)) {
                // Execute the INSERT operation
                self::execute(
                    "INSERT INTO Notes(title,owner,created_at,edited_at,pinned,archived,weight) VALUES (:title,:owner,:created_at,:edited_at,:pinned,:archived,:weight)",
                    [
                        "title" => $this->title,
                        "owner" => $this->owner,
                        "created_at" => $this->created_at,
                        "edited_at" => $this->edited_at,
                        "pinned" => $this->pinned,
                        "archived" => $this->archived,
                        "weight" => $this->weight,
                    ]
                );
                $note = self::get_note_by_id(self::lastInsertId());
                $this->note_id = $note->note_id;
                return $this;
            } else {
                return $errors;
            }
        } else {
            self::execute('UPDATE Notes SET title = :title, pinned = :pinned, archived = :archived, weight = :weight, edited_at = NOW() WHERE id = :note_id', [
                'title' => $this->title,
                'pinned' => $this->pinned ? 1 : 0,
                'archived' => $this->archived ? 1 : 0,
                'weight' => $this->weight,
                'note_id' => $this->note_id,
            ]);
            return $this;
        }
    }
    public function is_weight_unique(int $id): int
    {
        $query = self::execute("SELECT id, MAX(weight) from notes where id = :note_id group by id", ["note_id" => $id]);
        $data = $query->fetch();
        return $data['id'];
    }


    public function get_note_up(User $user, int $note_id, int $weight, bool $pin): Note|false
    {
        $query = self::execute("
        SELECT * FROM notes n
        WHERE owner = :ownerid AND n.id <> :note_id AND archived = 0 AND pinned = :pin AND weight > :weight 
        ORDER BY weight LIMIT 1
        ", ["ownerid" => $user->id, "note_id" => $note_id, "pin" => $pin, "weight" => $weight]);

        $data = $query->fetch();
        if (!$data) {
            return false;
        } else
            return Note::create_note($data);
    }

    public function get_note_down(User $user, int $note_id, int $weight, bool $pin): Note|false
    {
        $query = self::execute("
        SELECT * FROM notes n
        WHERE owner = :ownerid AND n.id <> :note_id AND archived = 0 AND pinned = :pin AND weight < :weight 
        ORDER BY -weight LIMIT 1
        ", ["ownerid" => $user->id, "note_id" => $note_id, "pin" => $pin, "weight" => $weight]);

        $data = $query->fetch();
        if (!$data)
            return false;
        else
            return Note::create_note($data);
    }

    public function move_db(Note $second): Note
    {
        $weight_second = $second->get_weight();
        $second_id = $second->note_id;
        $weight_first = $this->weight;

        // étape intermédiaire pour éviter respecter unicité des poids par owner
        self::execute(
            'UPDATE notes SET weight = :weight_note2 WHERE id = :id_note2',
            ['id_note2' => $second->note_id, 'weight_note2' => 99]
        );

        self::execute(
            'UPDATE notes SET weight = :weight_note2 WHERE id = :id_note1',
            ['id_note1' => $this->note_id, 'weight_note2' => $weight_second]
        );

        self::execute(
            'UPDATE notes SET weight = :weight_note1 WHERE id = :id_note2',
            ['id_note2' => $second_id, 'weight_note1' => $weight_first]
        );


        return $this;
    }

    public static function get_note_by_id(int $note_id): Note|false
    {
        return Note::is_text_note($note_id) ? TextNote::get_note_by_id($note_id) : CheckListNote::get_note_by_id($note_id);
    }
    public static function is_text_note(int $id): bool
    {
        $query = self::execute("SELECT content FROM text_notes where id = :id", ["id" => $id]);
        $data = $query->fetchAll();
        return count($data) !== 0;
    }
    public static function create_note($data): Note|false
    {
        if (count($data) !== 0) {
            return Note::is_text_note($data['id']) ? new TextNote(
                $data['id'],
                $data['title'],
                $data['owner'],
                $data['created_at'],
                $data['pinned'],
                $data['archived'],
                $data['weight'],
                $data['edited_at']
            ) :
                new CheckListNote(
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
    public static function update_drag_and_drop($count, $idval)
    {
        self::execute("UPDATE notes SET weight = :count, WHERE id = :id", ['count' => $count, 'id' => $idval]);
    }
}
