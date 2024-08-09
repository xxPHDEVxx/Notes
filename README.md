# Projet App Notes

## Description

**App Notes** est une application web permettant une gestion complète des notes personnelles. Développée avec PHP pour le back-end et utilisant HTML, CSS, et JavaScript pour le front-end, l'application permet aux utilisateurs de créer, modifier, supprimer, trier et partager leurs notes. Elle supporte également l'archivage des notes pour une meilleure organisation. Le projet utilise une base de données MySQL, pré-configurée et accessible via phpMyAdmin, gérée avec XAMPP.

## Fonctionnalités

- **Gestion des notes** : Créez, modifiez, et supprimez vos notes.
- **Partage de notes** : Partagez vos notes avec d'autres utilisateurs de l'application.
- **Archivage** : Archivez les notes pour les conserver sans les afficher dans les listes principales.
- **Tri et recherche** : Tri par défaut et utilisez la fonction de recherche pour trouver rapidement une note par label.
- **Interface utilisateur intuitive** : L'interface est conçue pour être simple d'utilisation et agréable visuellement.

## Prérequis

- **XAMPP** (ou tout autre environnement Apache/MySQL/PHP)
- **phpMyAdmin** pour la gestion de la base de données
- **Navigateur Web** moderne pour accéder à l'application

## Installation

1. Clonez le dépôt GitHub du projet :

    ```bash
    git clone https://github.com/votre-utilisateur/votre-projet-notes.git
    cd votre-projet-notes
    ```

2. Placez le projet dans le répertoire `htdocs` de votre installation XAMPP (ou tout autre dossier racine de votre serveur web Apache).

3. Démarrez XAMPP et activez Apache et MySQL.

4. Créez la base de données MySQL en utilisant phpMyAdmin :
   - Importez le fichier SQL fourni dans le projet (`database.sql`) pour configurer la base de données.
   - Configurez le fichier `config.php` (ou un fichier équivalent) pour connecter l'application à la base de données.

5. Accédez à l'application via votre navigateur à l'adresse suivante :
    ```
    http://localhost/votre-projet-notes
    ```

## Utilisation

1. **Connexion** : Connectez-vous à l'application en utilisant l'un des comptes utilisateurs prédéfinis.
    - Liste des utilisateurs et mots de passe :
      * `boverhaegen@epfc.eu`, mot de passe : `Password1,`
      * `bepenelle@epfc.eu`, mot de passe : `Password1,`
      * `xapigeolet@epfc.eu`, mot de passe : `Password1,`
      * `mamichel@epfc.eu`, mot de passe : `Password1,`

2. **Gestion des notes** : Après connexion, commencez à créer et organiser vos notes. Vous pouvez également les partager avec d'autres utilisateurs.

3. **Recherche et tri** : Utilisez la barre de recherche pour trouver rapidement une note spécifique, ou triez vos notes selon différents critères.

4. **Partage** : Partagez une note avec d'autres utilisateurs en sélectionnant l'option de partage.

## Notes de version

### Version actuelle : 1.0.0

- **Fonctionnalités de base** : Gestion, partage, tri, et archivage des notes.
- **Interface utilisateur** : Une interface simple et épurée pour une gestion efficace des notes.

### Liste des utilisateurs et mots de passe

- `boverhaegen@epfc.eu`, mot de passe : `Password1,`
- `bepenelle@epfc.eu`, mot de passe : `Password1,`
- `xapigeolet@epfc.eu`, mot de passe : `Password1,`
- `mamichel@epfc.eu`, mot de passe : `Password1,`

### Liste des bugs connus

- **Affichage CSS** : Quelques petits bugs d'affichage potentiels peuvent survenir selon le navigateur utilisé.
- **Recherche** : Bug mineur concernant la recherche, où les notes ne sont pas mises à jour correctement si elles sont modifiées pendant la navigation pour les checklists en JavaScript.
