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

            if (isset($_POST['title']) && $_POST['title'] != "" ) {
                $title = Tools::sanitize($_POST["title"]);
                $note = Note::get_note_by_id($id);
                $errors = $note->validate();
                if(empty($errors)){
    
                    $note->title = $title;
                    $note->persist();           
                }

            }

            if (isset($_POST['delete']) && $_POST['delete'] ) {
                $item_id = $_POST["delete"];
                $item = CheckListNoteItem::get_item_by_id($item_id);
                if ($item === false) {
                    throw new Exception("Undefined checklist item");
                }
                // Supprime l'élément de la liste de contrôle
                $item->delete();
            }
            if (isset($_POST['new']) && $_POST["new"] != "") {
                $new_item_content = Tools::sanitize($_POST['new']);
                $new_item = new CheckListNoteItem(5, $note->note_id, $new_item_content, 0);
                $new_item->persist();
            }
            $this->redirect("openNote", "edit/$id");
        }
    }

    public function save_edit_text_note() {
        $user = $this->get_user_or_redirect();
    
        // Vérifiez si les données POST sont présentes
        if (isset($_GET['param1'], $_POST['title'], $_POST['content'])) {
            $note_id = (int)$_GET['param1'];

            if ($note_id > 0) {
                $note = TextNote::get_note_by_id($note_id);
    
                // Vérifiez si la note existe et si l'utilisateur est le propriétaire
                if ($note && $note->owner == $user->id) {
                    // Validez le titre et le contenu
                    $note->title = (Tools::sanitize($_POST['title']));
                    $note->set_content((Tools::sanitize($_POST['content'])));
                    $note->update();
    
                    // Redirection vers la vue de la note
                    $this->redirect("openNote", "index", $note_id);
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


}
