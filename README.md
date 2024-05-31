# Projet PRWB 2324 - Groupe a03 - Google Keep

## Notes de version itération 1 

### Liste des utilisateurs et mots de passes

  * boverhaegen@epfc.eu, password "Password1,", utilisateur
  * bepenelle@epfc.eu, password "Password1,", utilisateur
  * xapigeolet@epfc.eu, password "Password1,", utilisateur
  * mamichel@epfc.eu, password "Password1,", utilisateu

### Liste des bugs connus

  * Fonctionnalités liées au view_shares sont manquantes ( toggle_permission, delete, add, ...).
  * Risques de failles de sécurité potentielles.
  * Soucis d'ajax (serveur inaccessible) au niveau des validations pour edit et add text note.
  * Drag and Drop ne modifie pas la base de donnée.
  * Use case B de l'itération 2 manquant.
  * Mauvaise gestion de création de note lorsque celle-ci est laissée vide (add_text_note)
  * Ajouter ou supprimer un item à la checklist_note mets automatique à jour la note avec ou sans confirmation.
  * ...

### Liste des fonctionnalités supplémentaires

### Divers

## Notes de version itération 2

...

## Notes de version itération 3 

# Projet PRWB 2324 - Groupe a03 - Google Keep

## Notes de version itération 1 

### Liste des utilisateurs et mots de passes

  * boverhaegen@epfc.eu, password "Password1,", utilisateur
  * bepenelle@epfc.eu, password "Password1,", utilisateur
  * xapigeolet@epfc.eu, password "Password1,", utilisateur
  * mamichel@epfc.eu, password "Password1,", utilisateu

### Liste des bugs connus

  * Fonctionnalités liées au view_shares sont manquantes ( toggle_permission, delete, add, ...).
  * Risques de failles de sécurité potentielles.
  * Soucis d'ajax (serveur inaccessible) au niveau des validations pour edit et add text note.
  * Drag and Drop ne modifie pas la base de donnée.
  * Use case B de l'itération 2 manquant.
  * Mauvaise gestion de création de note lorsque celle-ci est laissée vide (add_text_note)
  * Ajouter ou supprimer un item à la checklist_note mets automatique à jour la note avec ou sans confirmation.
  * ...

### Liste des fonctionnalités supplémentaires

### Divers

## Notes de version itération 2

...

## Notes de version itération 3 

  * itération 1 corrigée à 100% (hormis bug non remarqués).
  * Drag and Drop : ok.
  * Edit_Checklist_Note gestion en JS : ok ( 2 bugs mineurs décelés   -> gestion accent + validation newItem après ajout).
  * Edit_Text_Note validations : ok ( 1 bug mineur -> gestion accent).
  * Modal Delete : ok.
  * Modal Edit : ok ( 1 bug mineur -> modal s'affiche à la supression d'un item ).
  * Gestion partage : ok ( 1 bug mineur -> select ne reaparait pas après supression tant qu'on refresh pas)
  * Check_uncheck : ok -> oublié de l'ajouté sur le dernier commit.
  
  * petit bug affichage css potentiels.
  *  fonctionnalité search non aboutie.