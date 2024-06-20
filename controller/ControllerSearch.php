<?php

// Inclusion des dépendances et des classes nécessaires
require_once "framework/Controller.php";
require_once "framework/View.php";
require_once "model/User.php";
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
        // Récupération des notes de l'utilisateur
        $notes = $user->get_notes_search(null);

        if (isset($_POST['check'])) {
            $notes = $user->get_notes_search($_POST['check']);
        }
        //recherche
        (new View("search"))->show([
            "sharers" => $user->shared_by(),
            "currentPage" => "search",
            "labels" => $labels,
            "notes" => $notes
        ]);
    }
}
