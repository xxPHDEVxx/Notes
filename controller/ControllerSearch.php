<?php

// Inclusion des dépendances et des classes nécessaires
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";
require_once "model/Util.php";
require_once "framework/Tools.php";
require_once "model/NoteShare.php";
require_once "model/NoteLabel.php";

// Définition de la classe ControllerSearch, héritant de la classe Controller
class ControllerSearch extends Controller
{
    // Méthode pour afficher la page principale des notes de l'utilisateur
    public function index(): void
    {
        $user = $this->get_user_or_redirect();
        $labels = $user->get_labels();
        $notes = [];

        // Récupération des notes de l'utilisateur
        if (isset($_POST['check'])) {
            $notes = $user->get_notes_search($_POST['check']);
            $param1 = Util::url_safe_encode($notes);
            $param2 = Util::url_safe_encode($_POST['check']);
            $this->redirect('search', 'search', $param1, $param2);
        } else {
            //recherche
            (new View("search"))->show([
                "sharers" => $user->shared_by(),
                "currentPage" => "search",
                "labels" => $labels,
                "notes" => $notes
            ]);
        }
    }

    public function search(): void
    {
        $user = $this->get_user_or_redirect();
        $labels = $user->get_labels();

        if (isset($_GET["param1"], $_GET["param2"])) {
            $notes = Util::url_safe_decode($_GET["param1"]);
            $selected_labels = Util::url_safe_decode($_GET["param2"]);
            //recherche
            (new View("search"))->show([
                "sharers" => $user->shared_by(),
                "currentPage" => "search",
                "labels" => $labels,
                "notes" => $notes,
                "notes_coded" => $_GET["param1"],
                "labels_checked" => $selected_labels,
                "labels_checked_coded" => $_GET["param2"]
            ]);
        }
    }

    // Recherche de notes selon ses labels par Ajax
    public function search_service()
    {
        $notes = [];
        if (isset($_POST["check"])) {
            $user = $this->get_user_or_redirect();
            $notes = $user->get_notes_search($_POST["check"]);
        }
        // Retourner les notes en JSON
        echo json_encode($notes);
    }
}
