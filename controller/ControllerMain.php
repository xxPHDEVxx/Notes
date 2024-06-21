<?php
// Inclusion des fichiers nécessaires
require_once "framework/Controller.php"; 
require_once "framework/View.php"; 
require_once "model/User.php";
require_once "controller/ControllerUser.php"; 

// Définition de la classe ControllerMain, héritant de la classe Controller
class ControllerMain extends Controller
{

    // Méthode pour la page d'accueil
    public function index(): void
    {
        // Vérifie si un utilisateur est connecté
        if ($this->user_logged()) {
            // Redirige vers la page principale des notes si l'utilisateur est connecté
            $this->redirect("note", "index");
        } else {
            // Sinon, affiche la page de connexion
            $this->login();
        }
    }

    // Méthode pour gérer la connexion des utilisateurs
    public function login(): void
    {
        $mail = ''; // Variable pour stocker l'email de l'utilisateur
        $password = ''; // Variable pour stocker le mot de passe de l'utilisateur
        $errors = []; // Tableau pour les erreurs de validation

        // Vérifie si l'email et le mot de passe sont fournis
        if (isset($_POST['mail']) && isset($_POST['password'])) {
            $mail = $_POST['mail'];
            $password = $_POST['password'];

            // Valide les informations de connexion
            $errors = User::validate_login($mail, $password);

            // Si aucune erreur, connecte l'utilisateur
            if (empty($errors)) {
                $this->log_user(User::get_user_by_mail($mail));
            }
        }
        // Affiche la vue de connexion avec les données saisies et les erreurs
        (new View("login"))->show(["mail" => $mail, "password" => $password, "errors" => $errors]);
    }

    // Méthode pour gérer la déconnexion des utilisateurs
    public function logout(): void
    {
        // Obtient l'utilisateur actuel ou redirige s'il n'est pas connecté
        $user = $this->get_user_or_redirect();
        $sharers = $user->shared_by(); // Utilisateurs avec qui des notes sont partagées

        // Vérifie si le formulaire de déconnexion est soumis
        if (isset($_POST['logout'])) {
            // Déconnecte l'utilisateur
            Controller::logout();
        } else if (isset($_POST['no'])) {
            // Redirige vers les paramètres si l'utilisateur a annulé la déconnexion
            $this->redirect("settings", "settings");
        } else {
            // Affiche la vue de déconnexion
            (new View("logout"))->show(["user" => $user, "sharers" => $sharers]);
        }
    }

    // Méthode pour gérer l'inscription des nouveaux utilisateurs
    public function signup(): void
    {
        $mail = ''; // Variable pour stocker l'email de l'utilisateur
        $full_name = ''; // Variable pour stocker le nom complet de l'utilisateur
        $password = ''; // Variable pour stocker le mot de passe de l'utilisateur
        $password_confirm = ''; // Variable pour stocker la confirmation du mot de passe
        $errors = []; // Tableau pour les erreurs de validation

        // Vérifie si les informations d'inscription sont fournies
        if (isset($_POST['mail']) && isset($_POST['full_name']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
            $mail = $_POST['mail'];
            $full_name = $_POST['full_name'];
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];

            // Crée un nouvel utilisateur avec les informations fournies
            $user = new User($mail, Tools::my_hash($password), $full_name);

            // Valide l'unicité de l'email et les informations de l'utilisateur
            $errors = User::validate_unicity($mail);
            $errors = array_merge($errors, $user->validate());
            $errors = array_merge($errors, $user->validate_name());
            $errors = array_merge($errors, User::validate_passwords($password, $password_confirm, $user));

            // Si aucune erreur, enregistre l'utilisateur et le connecte
            if (count($errors) == 0) {
                $user->persist(); // Enregistre l'utilisateur dans la base de données
                $this->log_user($user); // Connecte l'utilisateur
            }
        }
        // Affiche la vue d'inscription avec les données saisies et les erreurs
        (new View("signup"))->show([
            "mail" => $mail,
            "full_name" => $full_name,
            "password" => $password,
            "password_confirm" => $password_confirm,
            "errors" => $errors
        ]);
    }
}
