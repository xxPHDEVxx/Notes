<?php
// Inclusion des fichiers nécessaires
require_once 'model/User.php'; 
require_once 'framework/View.php';
require_once 'framework/Controller.php';

// Définition de la classe ControllerUser, héritant de la classe Controller
class ControllerUser extends Controller
{

    // Méthode index vide, par défaut pour la classe de contrôleur
    public function index(): void
    {
        // Cette méthode est intentionnellement vide
    }

    // Méthode pour afficher les archives de l'utilisateur
    public function my_archives(): void
    {
        // Obtient l'utilisateur actuel ou redirige s'il n'est pas connecté
        $user = $this->get_user_or_redirect();
        // Récupère les archives de l'utilisateur
        $archives = $user->get_archives();
        // Affiche la vue des archives avec les données
        (new View("archives"))->show([
            "currentPage" => "my_archives", // Indique la page actuelle
            "archives" => $archives, // Données des archives
            "sharers" => $user->shared_by() // Utilisateurs avec qui les notes sont partagées
        ]);
    }

    // Méthode pour obtenir les notes partagées par un utilisateur spécifique
    public function get_shared_by(): void
    {
        $shared_notes_by = []; // Notes partagées par un utilisateur
        $shared_notes_as_editor = []; // Notes partagées où l'utilisateur est éditeur
        $shared_notes_as_reader = []; // Notes partagées où l'utilisateur est lecteur

        // Obtient l'utilisateur actuel ou redirige s'il n'est pas connecté
        $user = $this->get_user_or_redirect();

        // Vérifie si un identifiant de l'utilisateur partageant est passé en paramètre
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $shared_by = $_GET["param1"]; // Identifiant de l'utilisateur partageant
            // Obtient le nom complet de l'utilisateur partageant
            $shared_by_name = User::get_user_by_id($shared_by)->full_name;
            // Récupère les notes partagées par cet utilisateur
            $shared_notes_by = $user->get_shared_by($shared_by);

            // Trie les notes selon que l'utilisateur est éditeur ou lecteur
            foreach ($shared_notes_by as $shared) {
                if ($shared["editor"] == 1)
                    $shared_notes_as_editor[] = $shared; // Note où l'utilisateur est éditeur
                else
                    $shared_notes_as_reader[] = $shared; // Note où l'utilisateur est lecteur
            }
        }
        // Affiche la vue des notes partagées avec les données
        (new View("shared_notes"))->show([
            "currentPage" => $shared_by_name, // Nom de la page, basée sur le nom de l'utilisateur partageant
            "shared_by_name" => $shared_by_name, // Nom complet de l'utilisateur partageant
            "shared_notes_as_editor" => $shared_notes_as_editor, // Notes où l'utilisateur est éditeur
            "shared_notes_as_reader" => $shared_notes_as_reader, // Notes où l'utilisateur est lecteur
            "sharers" => $user->shared_by() // Utilisateurs avec qui les notes sont partagées
        ]);
    }
}
