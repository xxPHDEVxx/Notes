<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";
require_once "framework/Tools.php";
require_once "model/NoteShare.php";
require_once "model/ChecklistNote.php";

class ControllerNote extends Controller
{
    public function index(): void
    {
        $user = $this->get_user_or_redirect();
        $notes_pinned = $user->get_notes_pinned();
        $notes_unpinned = $user->get_notes_unpinned();
        (new View("notes"))->show([
            "currentPage" => "my_notes",
            "notes_pinned" => $notes_pinned,
            "notes_unpinned" => $notes_unpinned,
            "user" => $user,
            "sharers" => $user->shared_by()
        ]);
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
            var_dump($_POST["down"]);
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
        $this->get_user_or_redirect();

        $note_id = Tools::sanitize($_POST["note"]);
        $sharer = User::get_user_by_id($_POST['share']);
        $edit = ($_POST['edit'] == 0) ? true : false;
        $note_sh = NoteShare::get_share_note($note_id, $sharer->id);
        $note_sh->editor = $edit;
        $note_sh->persist();
        $this->redirect("note", "shares", $note_id);
    }

    public function delete_js()
    {
        $this->get_user_or_redirect();
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

    function extractIdsFromString($string)
    {
        // Initialiser un tableau pour stocker les IDs extraits
        $ids = array();

        // Séparer la chaîne en éléments individuels en utilisant la virgule comme délimiteur
        $elements = explode(",", $string);

        // Parcourir chaque élément et extraire l'ID en supprimant le préfixe "note_"
        foreach ($elements as $element) {
            // Supprimer le préfixe "note_"
            $id = substr($element, strpos($element, "_") + 1);

            // Ajouter l'ID à notre tableau d'IDs
            $ids[] = $id;
        }

        // Retourner le tableau d'IDs extraits
        return $ids;
    }

    public function drag_and_drop()
{
    // Vérifie si les données nécessaires sont présentes dans la requête POST
    if (
        isset(
            $_POST['moved'],
            $_POST['update'],
            $_POST['source'],
            $_POST['target'],
            $_POST['sourceItems'],
            $_POST['targetItems']
        )
    ) {
        // Récupère l'ID de la note déplacée
        $note_id = $_POST['moved'];

        // Récupère l'objet de la note à partir de l'ID
        $note = Note::get_note_by_id($note_id);

        // Extrait les IDs des éléments source et target à partir des chaînes JSON
        $source_ids = $this->extractIdsFromString($_POST['sourceItems']);
        $target_ids = $this->extractIdsFromString($_POST['targetItems']);

        // Détermine si la cible est "pinned" ou "unpinned"
        $target = $_POST['target'] == "pinned" ? 1 : 0;

        // Détermine si la source est "pinned" ou "unpinned"
        $source = $_POST['source'] == "pinned" ? 1 : 0;

        // Si la cible est différente de la source, effectue l'opération de "pin" ou "unpin"
        if ($target != $source) {
            // Si la cible est "pinned", épingle la note, sinon désépingle la note
            $target == 1 ? $note->pin() : $note->unpin();
            // Met à jour l'ordre des notes dans les listes source et target
            $note->new_order($source_ids);
            $note->new_order($target_ids);
        } else {
            // Si la cible est égale à la source, met simplement à jour l'ordre dans la source
            $note->new_order($source_ids);
        }
    }
}



    public function add_checklist_note()
    {
        $user = $this->get_user_or_redirect();
        $errors = [];
        $duplicateErrors = [];
        $duplicateItems = [];

        // Initialisation du tableau pour les éléments non vides
        $non_empty_items = [];

        // Vérification du titre
        if (isset($_POST['title'])) {
            if ($_POST['title'] == "") {
                $errors['title'] = "Title required";
            } else {
                $title = Tools::sanitize($_POST['title']);
                $note = new ChecklistNote(
                    0,
                    $title,
                    $user->id,
                    date("Y-m-d H:i:s"),
                    false,
                    false,
                    $user->get_max_weight()
                );
                $titleErrors = $note->validate_title();
                if (!empty($titleErrors)) {
                    $errors['title'] = implode($titleErrors);
                }
            }
        }

        // Vérification des éléments
        if (isset($_POST['items'])) {
            $items = $_POST['items'];
            foreach ($items as $key => $item) {
                if (!empty($item)) {
                    //on crée une instance pour vérifier la longueur de l'item
                    $checklistItem = new CheckListNoteItem(0, 0, $item, 0);
                    $contentErrors = $checklistItem->validate_item();
                    if (!empty($contentErrors)) {
                        $errors["item_$key"] = implode($contentErrors);
                    } else {
                        if (in_array($item, $duplicateItems)) {
                            $duplicateErrors["item_$key"] = "Items must be unique.";
                        } else {
                            $non_empty_items[$key] = $item;
                            $duplicateItems[] = $item;
                        }
                    }
                }
            }
        }

        // Combinaison des erreurs de doublons avec les autres erreurs
        $errors = array_merge($errors, $duplicateErrors);

        // Vérification finale et persistance
        if (empty($errors)) {
            if (isset($note)) {
                $note->persist();
                $note->new();

                foreach ($non_empty_items as $key => $content) {
                    $checklistNoteId = $note->note_id;
                    $checked = false;

                    $checklistItem = new CheckListNoteItem(
                        0,
                        $checklistNoteId,
                        $content,
                        $checked
                    );

                    $checklistItem->persist();
                }

                $this->redirect("note", "open_note", $note->note_id);
            }
        }

        // Afficher la vue avec les erreurs
        (new View("add_checklist_note"))->show(["errors" => $errors]);
    }


    // Supprime une note
    public function delete_note()
    {
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = Tools::sanitize($_GET["param1"]);
            $note = Note::get_note_by_id($note_id);
            $user = $this->get_user_or_redirect();
            if ($user->id == $note->owner) {
                (new View('delete_confirmation'))->show(['note_id' => $note_id]);
            } else {
                throw new Exception("vous n'êtes pas l'auteur de cette note");
            }
        } else {
            throw new Exception("Missing ID");
        }
    }

    public function delete_confirmation()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["delete"])) {
                if (isset($_GET['param1'])) {
                    $note_id = Tools::sanitize($_GET["param1"]);
                    $note = Note::get_note_by_id($note_id);
                    $user = $this->get_user_or_redirect();
                    if ($user->id == $note->owner) {
                        $note->delete($user);
                        $this->redirect("user", "my_archives");
                    } else {
                        throw new Exception("Vous n'êtes pas autorisé à supprimer cette note.");
                    }
                } else {
                    throw new Exception("Identifiant de la note manquant");
                }
            } else {
                // Si l'utilisateur annule la suppression, redirige vers la page de la note
                if (isset($_GET['param1'])) {
                    $note_id = $_GET['param1'];
                    $this->redirect("note", "open_note", $note_id);
                } else {
                    throw new Exception("Identifiant de la note manquant");
                }
            }
        }
    }

    public function edit_checklist()
    {
        $user = $this->get_user_or_redirect();
        $errors = [];
        $errorsItem = [];
        $edit_item = [];

        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = Tools::sanitize($_GET["param1"]);
            $note = CheckListNote::get_note_by_id($note_id);
            if ($note === false) {
                throw new Exception("Undefined note");
            }
            $user_id = $user->id;
            $archived = $note->in_my_archives($user_id);
            $pinned = $note->is_pinned($user_id);
            $is_shared_as_editor = $note->is_shared_as_editor($user_id);
            $is_shared_as_reader = $note->is_shared_as_reader($user_id);
            $body = $note->get_content();



            //verification si champ titre vide + si c'est le titre initial
            if (isset($_POST['title']) && $_POST['title'] == "") {
                $errors["title"] = "Title required";
            }

            if (isset($_POST['title']) && $_POST['title'] != $note->title) {
                $title = Tools::sanitize($_POST["title"]);
                $note = Note::get_note_by_id($note_id);
                $note->title = $title;
                $errors["title"] = implode($note->validate_title());
            }


            //action delete item
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

            //action add item
            if (isset($_POST['new']) && $_POST["new"] != "") {
                $new_item_content = Tools::sanitize($_POST['new']);
                $new_item = new CheckListNoteItem(0, $note->note_id, $new_item_content, false);

                if (!$new_item->is_unique()) {
                    $errors["items"] = "item must be unique";
                }
                $contentErrors = $new_item->validate_item();
                if (!empty($contentErrors)) {
                    $errors["items"] = implode($contentErrors);
                }

                //si item oke -> modif db
                if (empty($errors['items'])) {
                    $new_item->persist();
                    $this->redirect("note", "edit_checklist", $note_id);
                    exit;
                }
            }
        }
        if (isset($_POST["save"])) {
            //action edit item 
            // Vérification des éléments
            if (isset($_POST['items'])) {
                foreach ($_POST['items'] as $key => $item) {
                    $checklistItem = CheckListNoteItem::get_item_by_id($key);
                    $checklistItem->content = $item;
                    if (!$checklistItem->is_unique()) {
                        $errorsItem["item_$key"] = "item must be unique";
                    } else {
                        $contentErrors = $checklistItem->validate_item();
                        if (!empty($contentErrors)) {
                            $errorsItem["item_$key"] = implode("; ", $contentErrors);
                        }
                        if (empty($errorsItem["item_$key"])) {
                            $checklistItem->persist();
                        }
                    }
                }
                
            }   
            $errors = array_merge($errors, $errorsItem);
            if (empty($errors["title"]) && empty($errorsItem)) {
                $note->persist();
                $this->redirect("note", "open_note", $note->note_id);
            }
        }

        (new View("edit_checklist_note"))->show([
            "note" => $note,
            "note_id" => $note_id,
            "created" => $this->get_created_time($note_id),
            "edited" => $this->get_edited_time($note_id),
            "archived" => $archived,
            "is_shared_as_editor" => $is_shared_as_editor,
            "is_shared_as_reader" => $is_shared_as_reader,
            "content" => $body,
            "pinned" => $pinned,
            "user_id" => $user_id,
            "errors" => $errors
        ]);
    }

    public function save_edit_text_note()
    {
        $user = $this->get_user_or_redirect();
        $content_errors = [];
        $title_errors = [];
        $errors = [];

        if (isset($_POST['title']) && $_POST['title'] == "") {
            array_push($title_errors, "Title required");
        }

        if (isset($_GET['param1'], $_POST['title'], $_POST['content'])) {
            $note_id = (int) $_GET['param1'];
            if ($note_id > 0) {
                $note = TextNote::get_note_by_id($note_id);

                if ($note && $note->owner == $user->id) {
                    $note->title = Tools::sanitize($_POST['title']);
                    $note->set_content(Tools::sanitize($_POST['content']));

                    if ($note->validate_title() != null)
                        array_push($title_errors, $note->validate_title()[0]);
                    $content_errors = $note->validate_content();

                    if (!empty($content_errors) || !empty($title_errors)) {
                        (new View("edit_text_note"))->show([
                            "note" => $note,
                            "note_id" => $note_id,
                            "created" => $this->get_created_time($note_id),
                            "edited" => $this->get_edited_time($note_id),
                            "content" => $note->get_content(),
                            "title" => $note->title,
                            'errors' => $errors,
                            'content_errors' => $content_errors,
                            'title_errors' => $title_errors
                        ]);
                        exit();
                    }

                    $note->update();
                    $this->redirect("note", "open_note", $note_id);
                    exit();
                } else {
                    $errors = "Note introuvable ou vous n'avez pas la permission de la modifier.";
                }
            } else {
                $errors = "ID de note invalide.";
            }
        } else {
            var_dump($_GET['param1']);
            $errors = "Les informations requises sont manquantes.";
        }
    }


    public function save_add_text_note()
    {
        $user = $this->get_user_or_redirect();
        $content_errors = [];
        $title_errors = [];
        $errors = [];
        $title = "";
        $content = "";

        if (isset($_POST['title']) && $_POST['title'] == "") {
            array_push($title_errors, "Title required");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['title'], $_POST['content'])) {
                $title = Tools::sanitize($_POST['title']);
                $content = Tools::sanitize($_POST['content']);

                $note = new TextNote(
                    0,
                    $title,
                    $user->id,
                    date("Y-m-d H:i:s"),
                    0,
                    0,
                    $user->get_max_weight(),
                    null
                );
                $note->set_content($content);

                $content_errors = $note->validate_content();
                if ($note->validate_title() != null)
                    array_push($title_errors, $note->validate_title()[0]);

                if (empty($title_errors) && empty($content_errors)) {
                    $result = $note->persist();
                    if ($result instanceof TextNote) {
                        $note->update();
                        $this->redirect("note", "index", $result->note_id);
                        exit();
                    } else {
                        $errors[] = "Erreur lors de la sauvegarde de la note.";
                    }
                }
            } else {
                $errors[] = "Les informations requises pour le titre ou le contenu sont manquantes.";
            }
        }

        (new View("add_text_note"))->show([
            'note' => $note,
            'user' => $user,
            'errors' => $errors,
            'title' => $title,
            'content' => $content,
            'content_errors' => $content_errors,
            'title_errors' => $title_errors
        ]);
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
            "note" => $note,
            "note_id" => $note_id,
            "created" => $this->get_created_time($note_id),
            "edited" => $this->get_edited_time($note_id),
            "archived" => $archived,
            "is_shared_as_editor" => $is_shared_as_editor,
            "is_shared_as_reader" => $is_shared_as_reader,
            "note_body" => $body,
            "pinned" => $pinned,
            "user_id" => $user_id
        ]);
    }
    public function get_edited_time(int $note_id): string|bool
    {
        $edited_date = Note::get_edited_at($note_id);
        return $edited_date != null ? $this->get_elapsed_time($edited_date) : false;
    }
    public function get_created_time(int $note_id): string
    {
        $created_date = Note::get_created_at($note_id);
        return $this->get_elapsed_time($created_date);
    }

    public function get_elapsed_time(string $date): string
    {
        $localDateNow = new DateTime();
        $dateTime = new DateTime($date);
        $diff = $localDateNow->diff($dateTime);
        $res = '';
        if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0 && $diff->i == 0) {
            $res = $diff->s . " secondes ago.";
        } elseif ($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0 && $diff->i != 0) {
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
            $note->unpin();
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
            $content = $note->get_content();
        }
        ($note->get_type() == "TextNote" ? new View("edit_text_note") : new View("edit_checklist_note"))->show([
            "note" => $note,
            "note_id" => $note_id,
            "created" => $this->get_created_time($note_id),
            "edited" => $this->get_edited_time($note_id),
            "archived" => $archived,
            "is_shared_as_editor" => $is_shared_as_editor,
            "is_shared_as_reader" => $is_shared_as_reader,
            "content" => $content,
            "pinned" => $pinned,
            "user_id" => $user_id
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

    public function check_title_service()
    {
        $title_error = "";
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            if ($_POST['note'] != null) {
                $note_id = (int) str_replace("&quot;", "", $_POST["note"]);
                $note = Note::get_note_by_id($note_id);
                if ($note->validate_title_service($title) != null)
                    $title_error = $note->validate_title_service($title)[0];
            } else {
                if (Note::validate_new_title_service($title) != null)
                    $title_error = Note::validate_new_title_service($title)[0];
            }
            if (!empty($title_error)) {
                echo $title_error;
            }
        }
    }

    public function check_content_service()
    {
        $content_error = "";
        if (isset($_POST['content'])) {
            $content = $_POST['content'];
            if (Note::validate_content_service($content) != null)
                $content_error = Note::validate_content_service($content)[0];
            if (!empty($content_error)) {
                echo $content_error;
            }
        }
    }
}
