<?php

// Inclusion des dépendances nécessaires
require_once "framework/Controller.php"; // Classe de base pour les contrôleurs
require_once "framework/View.php"; // Classe pour la gestion des vues
require_once "model/User.php"; // Classe de gestion des utilisateurs

// Définition de la classe ControllerSettings, héritant de la classe Controller
class ControllerSettings extends Controller
{

    // Méthode pour afficher la page de paramètres de l'utilisateur
    public function settings(): void
    {
        // Obtient l'utilisateur actuel ou redirige s'il n'est pas connecté
        $user = $this->get_user_or_redirect();
        // Récupère les utilisateurs avec qui les notes sont partagées
        $sharers = $user->shared_by();
        $currentPage = "settings"; // Indique la page actuelle pour la vue
        // Affiche la vue des paramètres avec les données
        (new View("settings"))->show(["user" => $user, "sharers" => $sharers, "currentPage" => $currentPage]);
    }

    // Méthode pour éditer le profil de l'utilisateur, avec gestion des erreurs et validations
    public function edit_profile(): void
    {
        // Obtient l'utilisateur actuel ou redirige s'il n'est pas connecté
        $user = $this->get_user_or_redirect();
        $successMessage = null; // Initialisation du message de succès
        $errors = []; // Initialisation du tableau des erreurs

        // Vérifie si la méthode de requête est POST (soumission de formulaire)
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Nettoie les entrées de formulaire
            $newEmail = Tools::sanitize($_POST['email']);
            $newFullName = Tools::sanitize($_POST['fullName']);

            // Valide les nouvelles informations
            $errors = User::validate_edit($newEmail, $newFullName, $user);

            // Si aucune erreur n'est trouvée
            if (empty($errors)) {
                try {
                    // Vérifie si les informations n'ont pas changé
                    if ($user->mail == $newEmail && $user->full_name == $newFullName) {
                        $successMessage = "nothing"; // Rien à mettre à jour
                        $this->redirect("settings", "success_profile", $successMessage);
                    } else {
                        // Met à jour le profil de l'utilisateur
                        $user->update_profile($newFullName, $newEmail);
                        $successMessage = "update"; // Mise à jour réussie
                        $this->redirect("settings", "success_profile", $successMessage);
                    }
                } catch (Exception $e) {
                    // Capture les erreurs lors de la mise à jour
                    $errors[] = "Error updating profile : " . $e->getMessage();
                }
            }
        }
        // Affiche la vue pour éditer le profil avec les données
        (new View("edit_profile"))->show(["user" => $user, "successMessage" => $successMessage, "errors" => $errors]);
    }

    // Méthode pour afficher le message de succès après l'édition du profil
    public function success_profile(): void
    {
        // Obtient l'utilisateur actuel ou redirige s'il n'est pas connecté
        $user = $this->get_user_or_redirect();
        // Vérifie si un paramètre est passé dans l'URL
        if (isset($_GET["param1"])) {
            // Définit le message de succès en fonction du paramètre
            if ($_GET["param1"] == "nothing") {
                $successMessage = "Nothing to update!";
            } else {
                $successMessage = "Profile updated";
            }
            // Affiche la vue pour éditer le profil avec le message de succès
            (new View("edit_profile"))->show(["user" => $user, "successMessage" => $successMessage]);
        }
    }

    // Méthode pour changer le mot de passe de l'utilisateur
    public function change_password(): void
    {
        // Obtient l'utilisateur actuel ou redirige s'il n'est pas connecté
        $user = $this->get_user_or_redirect();

        $successMessage = null; // Initialisation du message de succès
        $errors = []; // Initialisation du tableau des erreurs

        // Vérifie si la méthode de requête est POST (soumission de formulaire)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les valeurs des champs du formulaire
            $currentPassword = $_POST['currentPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmNewPassword = $_POST['confirmNewPassword'];

            // Valide les informations de connexion de l'utilisateur
            $errors = User::validate_login($user->mail, $currentPassword);

            // Si aucune erreur de connexion n'est trouvée
            if (empty($errors)) {
                // Valide les nouveaux mots de passe
                $passwordErrors = User::validate_passwords($newPassword, $confirmNewPassword, $user);

                // Si aucune erreur de mot de passe n'est trouvée
                if (empty($passwordErrors)) {
                    try {
                        // Met à jour le mot de passe de l'utilisateur
                        $user->setPassword($newPassword);
                        $user->updatePassword($newPassword);
                        $successMessage = "success"; // Mise à jour réussie
                        $this->redirect("settings", "success_password", $successMessage);
                    } catch (Exception $e) {
                        // Capture les erreurs lors de la mise à jour du mot de passe
                        $errors[] = "Erreur lors de la mise à jour du mot de passe : " . $e->getMessage();
                    }
                } else {
                    // Combine les erreurs existantes avec les erreurs de mot de passe
                    $errors = array_merge($errors, $passwordErrors);
                }
            }
        }
        // Affiche la vue pour changer le mot de passe avec les données
        (new View("change_password"))->show(["user" => $user, "successMessage" => $successMessage, "errors" => $errors]);
    }

    // Méthode pour afficher le message de succès après le changement de mot de passe
    public function success_password(): void
    {
        // Obtient l'utilisateur actuel ou redirige s'il n'est pas connecté
        $user = $this->get_user_or_redirect();
        // Vérifie si un paramètre est passé dans l'URL
        if (isset($_GET["param1"])) {
            // Définit le message de succès en fonction du paramètre
            if ($_GET["param1"] == "success") {
                $successMessage = "Password changed successfully!";
            }
            // Affiche la vue pour changer le mot de passe avec le message de succès
            (new View("change_password"))->show(["user" => $user, "successMessage" => $successMessage]);
        }
    }

    // Méthode d'index vide, par défaut pour la classe de contrôleur
    public function index(): void
    {
    }
}
