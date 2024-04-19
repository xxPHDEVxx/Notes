<?php
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerOpenNote extends Controller
{
    public function index(): void
    {
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note_by_id($note_id);
            $user_id = $this->get_user_or_redirect()->id;
            $archived = $note->in_My_archives($user_id);
            $pinned = $note->is_pinned($user_id);
            $isShared_as_editor = $note->isShared_as_editor($user_id);
            $isShared_as_reader = $note->isShared_as_reader($user_id);
            $body = $note->get_content();
        }
        ($note->get_type() == "TextNote" ? new View("open_text_note") : new View("open_checklist_note"))->show([
            "note" => $note, "note_id" => $note_id, "created" => $this->get_created_time($note_id), "edited" => $this->get_edited_time($note_id), "archived" => $archived, "isShared_as_editor" => $isShared_as_editor, "isShared_as_reader" => $isShared_as_reader, "note_body" => $body, "pinned" => $pinned
        , "user_id" => $user_id]);
    }


    public function get_created_time(int $note_id): String
    {
        $created_date = Note::get_created_at($note_id);
        return $this->get_elapsed_time($created_date);
    }
    public function get_edited_time(int $note_id): String | bool
    {
        $edited_date = Note::get_edited_at($note_id);
        return $edited_date != null ? $this->get_elapsed_time($edited_date) : false;
    }


    public function get_elapsed_time(String $date): String
    {
        $localDateNow = new DateTime();
        $dateTime = new DateTime($date);
        $diff = $localDateNow->diff($dateTime);
        $res = '';
        if ($diff->y == 0 && $diff->m == 0 && $diff->d == 0 && $diff->h == 0 && $diff->i == 0) {
            $res = $diff->s . "secondes ago.";
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
        $this->redirect("openNote", "index/$note_id");
    }

    public function pin(): void
    {
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note_by_id($note_id);
            $note->pin();
            $this->index();
        }
    }
    public function unpin(): void
    {
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note_by_id($note_id);
            $note->unpin();
            $this->index();
        }
    }
    public function archive(): void
    {
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note_by_id($note_id);
            $note->archive();
            $this->redirect();
        }
    }

    public function unarchive(): void
    {
        if (isset($_GET["param1"]) && isset($_GET["param1"]) !== "") {
            $note_id = $_GET["param1"];
            $note = Note::get_note_by_id($note_id);
            $note->unarchive();
            $this->redirect();
        }
    }

    public function edit() {
        $user = $this->get_user_or_redirect();
    
        // Vérifie que l'ID de la note est présent dans l'URL
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $note_id = $_GET["param1"];
            // Récupération de la note par son ID
            $note = TextNote::get_note_by_id($note_id);
    
            // Si la note existe
            if ($note) {
                $user_id = $user->id;
                $archived = $note->in_My_archives($user_id);
                $pinned = $note->is_pinned($user_id);
                $isShared_as_editor = $note->isShared_as_editor($user_id);
                $isShared_as_reader = $note->isShared_as_reader($user_id);
                $content = $note->get_content();
    
                // Si la note est une TextNote et que l'utilisateur est le propriétaire
                if ($note instanceof TextNote && $note->owner == $user_id) {
                    (new View("edit_text_note"))->show([
                        "note" => $note,
                        "note_id" => $note_id,
                        "created" => $this->get_created_time($note_id),
                        "edited" => $this->get_edited_time($note_id),
                        "content" => $content,
                        "archived" => $archived,
                        "isShared_as_editor" => $isShared_as_editor,
                        "isShared_as_reader" => $isShared_as_reader,
                        "pinned" => $pinned
                    ]);
                } else {
                    echo "Note not found or you do not have permission to edit it.";
                }
            } else {
                echo "No note found with the provided ID.";
            }
        } else {
            echo "No note ID provided.";
        }
    }
    
    
    
    

    public function save_edit_text_note() {
        $user = $this->get_user_or_redirect();
    
        // Vérifiez si les données POST sont présentes
        if (isset($_POST['note_id'], $_POST['title'], $_POST['content'])) {
            $note_id = (int)$_POST['note_id'];
            echo $note_id;

            if ($note_id > 0) {
                $note = TextNote::get_note_by_id($note_id);
    
                // Vérifiez si la note existe et si l'utilisateur est le propriétaire
                if ($note && $note->owner == $user->id) {
                    // Validez le titre et le contenu
                    $note->title = htmlspecialchars(trim($_POST['title']));
                    $note->set_content(htmlspecialchars(trim($_POST['content'])));
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
