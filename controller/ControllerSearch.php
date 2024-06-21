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
        // Obtient l'utilisateur actuel ou redirige s'il n'est pas connecté
        $user = $this->get_user_or_redirect();
        // Récupère les labels de l'utilisateur
        $labels = $user->get_labels();
        // Initialise un tableau pour les notes
        $notes = [];

        // Vérifie si des labels sont cochés dans la requête POST
        if (isset($_POST['check'])) {
            // Récupère les notes correspondant aux labels cochés
            $notes = $user->get_notes_search($_POST['check']);
            // Encode les notes et les labels cochés pour les inclure dans l'URL
            $param1 = Util::url_safe_encode($notes);
            $param2 = Util::url_safe_encode($_POST['check']);
            // Redirige vers l'action de recherche avec les paramètres encodés
            $this->redirect('search', 'search', $param1, $param2);
        } else {
            // Affiche la vue de recherche avec les données
            (new View("search"))->show([
                "sharers" => $user->shared_by(), // Notes partagées par d'autres utilisateurs
                "currentPage" => "search", // Indique la page actuelle pour la vue
                "labels" => $labels, // Labels de l'utilisateur
                "notes" => $notes // Notes récupérées
            ]);
        }
    }

    // Méthode pour traiter la recherche avec des paramètres encodés
    public function search(): void
    {
        // Obtient l'utilisateur actuel ou redirige s'il n'est pas connecté
        $user = $this->get_user_or_redirect();
        // Récupère les labels de l'utilisateur
        $labels = $user->get_labels();

        // Vérifie si les paramètres nécessaires sont présents dans la requête GET
        if (isset($_GET["param1"], $_GET["param2"])) {
            // Décode les paramètres pour obtenir les notes et les labels sélectionnés
            $notes = Util::url_safe_decode($_GET["param1"]);
            $selected_labels = Util::url_safe_decode($_GET["param2"]);
            // Affiche la vue de recherche avec les données déduites
            (new View("search"))->show([
                "sharers" => $user->shared_by(), // Notes partagées par d'autres utilisateurs
                "currentPage" => "search", // Indique la page actuelle pour la vue
                "labels" => $labels, // Labels de l'utilisateur
                "notes" => $notes, // Notes récupérées
                "notes_coded" => $_GET["param1"], // Paramètres encodés pour les notes
                "labels_checked" => $selected_labels, // Labels sélectionnés
                "labels_checked_coded" => $_GET["param2"] // Paramètres encodés pour les labels sélectionnés
            ]);
        }
    }

    // Méthode pour rechercher des notes selon leurs labels via AJAX
    public function search_service()
    {
        // Initialise un tableau pour les notes
        $notes = [];
        // Vérifie si des labels sont cochés dans la requête POST
        if (isset($_POST["check"])) {
            // Obtient l'utilisateur actuel ou redirige s'il n'est pas connecté
            $user = $this->get_user_or_redirect();
            // Récupère les notes correspondant aux labels cochés
            $notes = $user->get_notes_search($_POST["check"]);
            // Encode les notes et les labels cochés pour les inclure dans la réponse
            $notes_coded = Util::url_safe_encode($notes);
            $labels_checked_coded = Util::url_safe_encode($_POST["check"]);

            // Prépare les données pour la réponse en JSON
            $data = [
                'notes' => $notes,
                'notes_coded' => $notes_coded,
                'labels_checked_coded' => $labels_checked_coded
            ];
        } else {
            // Prépare une réponse vide si aucune donnée n'est reçue
            $data = "";
        }
        // Retourne les notes en JSON
        echo json_encode($data);
    }
}
