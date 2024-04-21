<?php

require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";
require_once "framework/Tools.php";

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

    public function share_note()
    {
        $user = $this->get_user_or_redirect();
        (new View("share"))->show();
    }


    public function add_note(): void
    {
        (new view("add_text_note"))->show();
    }
    public function add_checklist_note()
    {
        $user = $this->get_user_or_redirect();
        $errors = [];
        // Vérification des doublons pour les éléments
        $duplicateErrors = [];
        $duplicateItems = [];

        if(isset($_POST['title']) && $_POST['title'] == "") {
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
            foreach ($non_empty_items as $key ) {
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
            
            $this->redirect("openNote", "index", $note->note_id);
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

    public function edit_checklist_note(): void
    {
        $user = $this->get_user_or_redirect();
        $errors = [];
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $id = $_GET['param1'];

            $note = CheckListNote::get_note_by_id($id);
            // Vérifie si la note existe
            if ($note === false) {
                throw new Exception("Undefined note");
            }
            if (isset($_POST["title"]) && $_POST["title"] != "") {
                $title = Tools::sanitize($_POST["title"]);
                $note = Note::get_note_by_id($id);
                $errors = $note->validate();
                if (empty($errors)) {

                    $note->title = $title;
                    $note->persist();
                }
            }
            if (isset($_POST['delete']) && $_POST['delete']) {
                $item_id = $_POST["delete"];
                $item = CheckListNoteItem::get_item_by_id($item_id);
                if ($item === false) {
                    throw new Exception("Undefined checklist item");
                }
                // Supprime l'élément de la liste de contrôle
                $item->delete();
                $this->redirect("openNote", "edit/$id");
            }
            if (isset($_POST['new']) && $_POST["new"] != "") {
                $new_item_content = Tools::sanitize($_POST['new']);
                $new_item = new CheckListNoteItem(5, $note->note_id, $new_item_content, 0);
                $new_item->persist();
                $this->redirect("openNote", "edit/$id");
            }
            $this->redirect("openNote", "index/$id");
        }
    }

    public function save_edit_text_note() {
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
                        $this->redirect("openNote", "edit", $note_id);
                        exit();
                    }
    
                    // Si tout est correct, mettre à jour la note
                    $note->update();
    
                    // Redirection vers la vue de la note
                    $this->redirect("openNote", "index", $note_id);
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

    public function save_add_text_note() {
        $user = $this->get_user_or_redirect();
    
        if (isset($_POST['title'], $_POST['content'])) {
            $title = Tools::sanitize($_POST['title']);
            $content = Tools::sanitize($_POST['content']);
            
            // Vérifier la longueur du titre avant de procéder
            
            if ((strlen($title) > 2) && (((strlen($content) > 4 && strlen($content) <= 800)) || strlen($content) == 0)){
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
                    $this->redirect("openNote", "index", $result->note_id);
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
    
}
