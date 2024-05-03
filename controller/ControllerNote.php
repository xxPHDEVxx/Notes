<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";
require_once "framework/Tools.php";
require_once "model/NoteShare.php";

class ControllerNote extends Controller
{
    public function index(): void
    {
        $user = $this->get_user_or_redirect();
        $notes_pinned = $user->get_notes_pinned();
        $notes_unpinned = $user->get_notes_unpinned();
        $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
        (new View("notes"))->show(["currentPage" => "my_notes", "notes_pinned" => $notes_pinned, "notes_unpinned" => $notes_unpinned,  "user" => $user, "sharers" => $user->shared_by()]);
    }

    public function move_up(): void
    {
        $user = $this->get_user_or_redirect();
        if (isset($_POST["up"]) && $_POST["up"] != "") {
            $id = $_POST["up"];
            $note = Note::get_note_by_id($id);
            if ($note === false)
                throw new Exception("undefined note");
            $other = $note->get_note_up($user, $id, $note->get_weight(), $note->isPinned());
            $note->move_db($other);
            $this->redirect("note", "index");
        } else {
            throw new Exception("Missing ID");
        }
    }
    public function move_down(): void
    {
        $user = $this->get_user_or_redirect();
        if (isset($_POST["down"]) && $_POST["down"] != "") {
            $id = $_POST["down"];
            $note = Note::get_note_by_id($id);
            if ($note === false)
                throw new Exception("undefined note");
            $other = $note->get_note_down($user, $id, $note->get_weight(), $note->isPinned());
            $other->move_db($note);
            $this->redirect("note", "index");
        } else {
            throw new Exception("Missing ID");
        }
    }
    public function shares()
    {
        $errors = [];
        $note = "";
        $connected = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = filter_var($_GET['param1'], FILTER_VALIDATE_INT);
            if ($note_id === false) {
                $errors = "invalid note";
            } else {
                $note = Note::get_note_by_id($note_id);
            }

            if ($note->owner != $connected->id) {
                $err = "pas la bonne personne connecté";
                Tools::abort($err);
            }

            //données visibles sur la vue
            $sharers = $note->get_shared_users();
            $others = [];
            $all_users = User::get_users();
            // Parcourir tous les utilisateurs
            foreach ($all_users as $us) {
                $is_shared = false;
                // Vérifier si l'utilisateur est déjà partagé avec la note
                foreach ($sharers as $shared_user) {
                    if ($shared_user[0] == $us->id) {
                        $is_shared = true;
                    }
                }
                // Si l'utilisateur n'est pas partagé et qu'il n'est pas celui connecté, l'ajouter à la liste
                if (!$is_shared && $us->id != $connected->id) {
                    $others[] = $us;
                }
            }

            //vérifier qu'on a une bonne valeur pour le user et l'editor
            if (isset($_POST['user'], $_POST['editor']) && ($_POST["user"] == "null" || $_POST["editor"] == "null")) {
                $errors[] = "erreurs";
            }

            if (isset($_POST['user'], $_POST['editor']) && empty($errors)) {
                $nv_us = User::get_user_by_id($_POST['user']);
                $editor = ($_POST['editor'] == 1) ? true : false;;
                $note_share = new NoteShare($note_id, $nv_us->id, $editor);
                $note_share->persist();
                $this->redirect("note", "shares", $note_id);
            }
        }



        (new View("share"))->show(["sharers" => $sharers, "others" => $others, "note" => $note]);
    }

    public function toggle_permission()
    {
        $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = Tools::sanitize($_GET["param1"]);

            // execution du form delete et toggle
            if (isset($_POST["action"])) {
                $action = $_POST["action"];
                // Exécuter les actions en fonction de la valeur soumise
                if ($action == "toggle") {
                    //on récupére une note share existante et on fait la modification dessus 
                    $sharer = User::get_user_by_id($_POST['share']);
                    $edit = ($_POST['edit'] == 0) ? true : false;
                    $note_sh = NoteShare::get_share_note($note_id, $sharer->id);
                    $note_sh->editor = $edit;
                    $note_sh->persist();
                    $this->redirect("note", "shares", $note_id);
                } elseif ($action == "delete") {
                    //on récupére la note share existante et on la supprime
                    $sharer = User::get_user_by_id($_POST['share']);
                    $note_sh = NoteShare::get_share_note($note_id, $sharer->id);
                    $note_sh->delete();
                    $this->redirect("note", "shares", $note_id);
                }
            }
        }
    }

    public function toggle_js()
    {
        $note_id = Tools::sanitize($_POST["note"]);
        $sharer = User::get_user_by_id($_POST['share']);
        $edit = ($_POST['edit'] == 0) ? true : false;
        $note_sh = NoteShare::get_share_note($note_id, $sharer->id);
        $note_sh->editor = $edit;
        $note_sh->persist();
        $this->redirect("note", "shares", $note_id);
    }

    public function delete_js() {
        $note_id = Tools::sanitize($_POST["note"]);
        $sharer = User::get_user_by_id($_POST['share']);
        $note_sh = NoteShare::get_share_note($note_id, $sharer->id);
        $note_sh->delete();
        $this->redirect("note", "shares", $note_id);
    }
    public function add_note(): void
    {
        (new view("add_text_note"))->show();
    }

    public function drag_and_drop()
    {

        if (isset($_POST['arrayorder'], $_POST['update'])) {
            $array = $_POST['arrayorder'];
            if ($_POST['update'] == "update") {
                $count = 1;
                foreach ($array as $idval) {
                    Note::update_drag_and_drop($count, $idval);
                    $count++;
                }
            }
        }
    }



    public function add_checklist_note()
    {
        $user = $this->get_user_or_redirect();
        $errors = [];
        // Vérification des doublons pour les éléments
        $duplicateErrors = [];
        $duplicateItems = [];

        if (isset($_POST['title']) && $_POST['title'] == "") {
            $errors['title'] = "Title required";
        }
        if (isset($_POST['title'], $_POST['items']) && $_POST['title'] != "") {
            $title = Tools::sanitize($_POST['title']);
            $items = $_POST['items'];
            // Initialisation d'un tableau pour les éléments non vides
            $non_empty_items = [];

            // Parcours des éléments pour ne sauvegarder que les non vides
            foreach ($items as $item) {
                if (!empty($item)) {
                    $non_empty_items[] = $item;
                }
            }
            $note = new ChecklistNote(
                0,
                $title,
                $user->id,
                date("Y-m-d H:i:s"),
                false,
                false,
                0
            );
            $errors = $note->validate_title();


            foreach ($non_empty_items as $key => $item) {
                if (in_array($item, $duplicateItems)) {
                    // Stocker l'erreur de doublon avec l'indice correspondant
                    $duplicateErrors["item_$key"] = "Items must be unique.";
                }
                $duplicateItems[] = $item;
            }


            // Combinaison des erreurs de doublons avec d'autres erreurs
            $errors = array_merge($errors, $duplicateErrors);
        }
        if (empty($errors) && isset($_POST['title'], $_POST['items']) && $_POST['title'] != "") {
            $note->persist();
            $note->new();
            // Parcours des erreurs de doublons
            foreach ($non_empty_items as $key) {
                // Création d'une nouvelle instance de CheckListNoteItem
                $content = $key; // Récupération du contenu de l'élément
                $checklistNoteId = $note->note_id; // Récupération de l'identifiant de la note de checklist
                $checked = false; // Par défaut, l'élément n'est pas coché

                // Création de l'instance CheckListNoteItem
                $checklistItem = new CheckListNoteItem(
                    0, // L'identifiant sera généré automatiquement par la base de données
                    $checklistNoteId,
                    $content,
                    $checked
                );

                // Enregistrement de l'élément dans la base de données
                $checklistItem->persist();
            }

            $this->redirect("note", "open_note", $note->note_id);
        }

        // Afficher la vue avec les erreurs
        (new View("add_checklist_note"))->show(["errors" => $errors]);
    }

    // Supprime une note
    public function delete_note()
    {
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET['param1'];
            $note = Note::get_note_by_id($note_id);
            $user = $this->get_user_or_redirect();
            if ($note->delete($user)) {
                // Rediriger l'utilisateur vers la liste des notes après la suppression
                $this->redirect("user", "my_archives");
            } else {
                throw new Exception("vous n'êtes pas l'auteur de cette note");
            }
        } else {
            throw new Exception("Missing ID");
        }
    }

    public function edit_checklist(): void
    {

        $user = $this->get_user_or_redirect();
        $errors = [];
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = Tools::sanitize($_GET["param1"]);
            $note = CheckListNote::get_note_by_id($note_id);
            if ($note === false) {
                throw new Exception("Undefined note");
            }
            $user_id = $this->get_user_or_redirect()->id;
            $archived = $note->in_my_archives($user_id);
            $pinned = $note->is_pinned($user_id);
            $is_shared_as_editor = $note->is_shared_as_editor($user_id);
            $is_shared_as_reader = $note->is_shared_as_reader($user_id);
            $body = $note->get_content();




            if (isset($_POST['title']) && $_POST['title'] == "") {
                $errors = "Title required";
            }

            if (isset($_POST['title']) && $_POST['title'] != "") {
                $title = Tools::sanitize($_POST["title"]);
                $note = Note::get_note_by_id($note_id);
                $note->title = $title;
                $errors = $note->validate_title();
            }


            if (isset($_POST['delete']) && $_POST['delete']) {
                $item_id = $_POST["delete"];
                $item = CheckListNoteItem::get_item_by_id($item_id);
                if ($item === false) {
                    throw new Exception("Undefined checklist item");
                }
                // Supprime l'élément de la liste de contrôle
                $item->delete();
                $this->redirect("note", "edit_checklist", $note_id);
            }
            if (isset($_POST['new']) && $_POST["new"] != "") {
                $new_item_content = Tools::sanitize($_POST['new']);
                $new_item = new CheckListNoteItem(0, $note->note_id, $new_item_content, false);
                $new_item->persist();
            }
        }
        if (empty($errors) && (isset($_POST['title']) && $_POST['title'] != "")) {
            $note->persist();
            $this->redirect("note", "open_note", $note->note_id);
        }

        (new View("edit_checklist_note"))->show([
            "note" => $note, "note_id" => $note_id, "created" => $this->get_created_time($note_id), "edited" => $this->get_edited_time($note_id), "archived" => $archived, "is_shared_as_editor" => $is_shared_as_editor, "is_shared_as_reader" => $is_shared_as_reader, "note_body" => $body, "pinned" => $pinned, "user_id" => $user_id, "errors" => $errors
        ]);
    }

    public function save_edit_text_note()
    {
        $user = $this->get_user_or_redirect();
        $errors = [];
        // Vérifiez si les données POST sont présentes
        if (isset($_GET['param1'], $_POST['title'], $_POST['content'])) {
            $note_id = (int)$_GET['param1'];
            if ($note_id > 0) {
                $note = TextNote::get_note_by_id($note_id);

                // Vérifiez si la note existe et si l'utilisateur est le propriétaire
                if ($note && $note->owner == $user->id) {
                    // Sanitize input
                    $note->title = Tools::sanitize($_POST['title']);
                    $note->set_content(Tools::sanitize($_POST['content']));

                    // Valider le titre et contenu
                    $_SESSION['edit_errors'] = []; // Réinitialiser les erreurs de session avant la validation
                    $titleErrors = $note->validate_title();
                    $contentErrors = $note->validate_content();
                    $errors = array_merge($titleErrors, $contentErrors);

                    if (!empty($errors)) {
                        // Stocker l'erreur de titre dans la session
                        $_SESSION['edit_errors'] = $errors;
                        $this->redirect("note", "edit", $note_id);
                        exit();
                    }

                    // Si tout est correct, mettre à jour la note
                    $note->update();

                    // Redirection vers la vue de la note
                    $this->redirect("note", "open_note", $note_id);
                    exit();
                } else {
                    echo "Note introuvable ou vous n'avez pas la permission de la modifier.";
                }
            } else {
                echo "ID de note invalide.";
            }
        } else {
            echo "Les informations requises sont manquantes.";
        }
    }

    public function save_add_text_note()
    {
        $user = $this->get_user_or_redirect();

        if (isset($_POST['title'], $_POST['content'])) {
            $title = Tools::sanitize($_POST['title']);
            $content = Tools::sanitize($_POST['content']);

            // Vérifier la longueur du titre avant de procéder

            if ((strlen($title) > 2) && (((strlen($content) > 4 && strlen($content) <= 800)) || strlen($content) == 0)) {
                $note = new TextNote(
                    0,
                    $title,
                    $user->id,
                    date("Y-m-d H:i:s"),
                    0,
                    0,
                    0,
                    null
                );

                // Appeler persist pour insérer ou mettre à jour la note
                $result = $note->persist();
                $note->set_content($content);
                $note->update();
                if ($result instanceof TextNote) {
                    $this->redirect("note", "open_note", $result->note_id);
                    exit();
                } else {
                    // Gérer les erreurs de persistance
                    echo "Erreur lors de la sauvegarde de la note : <br/>";
                    foreach ($result as $error) {
                        echo $error . "<br/>";
                    }
                }
            } else {
                // Gérer l'erreur de longueur du titre
                $_SESSION['edit_errors'] = ['Respectez les validations.'];
                $this->redirect("note", "add_note");
                exit();
            }
        } else {
            echo "Les informations requises pour le titre ou le contenu sont manquantes.";
        }
    }

    public function open_note()
    {
        $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note_by_id($note_id);
            $user_id = $this->get_user_or_redirect()->id;
            $archived = $note->in_my_archives($user_id);
            $pinned = $note->is_pinned($user_id);
            $is_shared_as_editor = $note->is_shared_as_editor($user_id);
            $is_shared_as_reader = $note->is_shared_as_reader($user_id);
            $body = $note->get_content();
        }
        ($note->get_type() == "TextNote" ? new View("open_text_note") : new View("open_checklist_note"))->show([
            "note" => $note, "note_id" => $note_id, "created" => $this->get_created_time($note_id), "edited" => $this->get_edited_time($note_id), "archived" => $archived, "is_shared_as_editor" => $is_shared_as_editor, "is_shared_as_reader" => $is_shared_as_reader, "note_body" => $body, "pinned" => $pinned, "user_id" => $user_id
        ]);
    }
    public function get_edited_time(int $note_id): String | bool
    {
        $edited_date = Note::get_edited_at($note_id);
        return $edited_date != null ? $this->get_elapsed_time($edited_date) : false;
    }
    public function get_created_time(int $note_id): String
    {
        $created_date = Note::get_created_at($note_id);
        return $this->get_elapsed_time($created_date);
    }

    public function get_elapsed_time(String $date): String
    {
        $localDateNow = new DateTime();
        $dateTime = new DateTime($date);
        $diff = $localDateNow->diff($dateTime);
        $res = '';
        if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0 && $diff->i == 0) {
            $res = $diff->s . " secondes ago.";
        } elseif ($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0  && $diff->i != 0) {
            $res = $diff->i . " minutes ago.";
        } elseif ($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h != 0) {
            $res = $diff->h . " hours ago.";
        } elseif ($diff->y == 0 && $diff->m == 0 && $diff->d != 0) {
            $res = $diff->d . " days ago.";
        } elseif ($diff->y == 0 && $diff->m != 0) {
            $res = $diff->m . " month ago.";
        } else if ($diff->y != 0) {
            $res = $diff->y . " years ago.";
        }
        return $res;
    }
    public function update_checked(): void
    {
        $this->get_user_or_redirect();
        if (isset($_POST["check"])) {
            $checklist_item_id = $_POST["check"];
            $note_id = CheckListNoteItem::get_checklist_note($checklist_item_id);
            $checked = true;
            CheckListNoteItem::update_checked($checklist_item_id, $checked);
        } elseif (isset($_POST["uncheck"])) {
            $checklist_item_id = $_POST["uncheck"];
            $note_id = CheckListNoteItem::get_checklist_note($checklist_item_id);
            $checked = false;
            CheckListNoteItem::update_checked($checklist_item_id, $checked);
        }
        $this->redirect("note", "open_note/$note_id");
    }

    public function pin(): void
    {
        $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note_by_id($note_id);
            $note->pin();
            $this->redirect("note", "open_note", $note_id);
        }
    }
    public function unpin(): void
    {
        $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note_by_id($note_id);
            $note->unpin();
            $this->redirect("note", "open_note", $note_id);
        }
    }
    public function archive(): void
    {
        $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note_by_id($note_id);
            $note->archive();
            $this->redirect("note", "open_note", $note_id);
        }
    }

    public function unarchive(): void
    {
        $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note_by_id($note_id);
            $note->unarchive();
            $this->redirect("note", "open_note", $note_id);
        }
    }

    public function edit(): void
    {

        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note_by_id($note_id);
            $user_id = $this->get_user_or_redirect()->id;
            $archived = $note->in_my_archives($user_id);
            $pinned = $note->is_pinned($user_id);
            $is_shared_as_editor = $note->is_shared_as_editor($user_id);
            $is_shared_as_reader = $note->is_shared_as_reader($user_id);
            $body = $note->get_content();
        }
        ($note->get_type() == "TextNote" ? new View("edit_text_note") : new View("edit_checklist_note"))->show([
            "note" => $note, "note_id" => $note_id, "created" => $this->get_created_time($note_id), "edited" => $this->get_edited_time($note_id), "archived" => $archived, "is_shared_as_editor" => $is_shared_as_editor, "is_shared_as_reader" => $is_shared_as_reader, "note_body" => $body, "pinned" => $pinned, "user_id" => $user_id
        ]);
    }

    // Ouvre la vue d'ajout d'une note
    public function add_text_note(): void
    {
        $user_id = $this->get_user_or_redirect()->id;

        // Créez une instance de vue pour l'ajout de note texte
        $view = new View("add_text_note");

        // Prépare les données par défaut pour initialiser la vue
        $data = [
            "user_id" => $user_id,
            "note_id" => null,
            "created" => date("Y-m-d H:i:s"),
            "edited" => null,
            "archived" => 0,
            "is_shared_as_editor" => 0,
            "is_shared_as_reader" => 0,
            "content" => "",
            "pinned" => 0
        ];

        $view->show($data);
    }
}
