$(document).ready(function() {
    // Cette fonction est exécutée lorsque le DOM est complètement chargé

    // Activation de la fonctionnalité de tri pour la liste "pinned"
    $("#pinned").sortable({ 
        connectWith: "#unpinned", // Permet de connecter cette liste avec la liste "unpinned"
        update: function(event, ui) { // Cette fonction est appelée lorsque l'ordre des éléments est mis à jour
            // Sérialisation de l'ordre des éléments et ajout d'un paramètre de mise à jour
            var order = $(this).sortable("serialize") + '&update=update';

            // Envoi d'une requête AJAX pour mettre à jour l'ordre des éléments côté serveur
            $.ajax({
                url: "note/drag_and_drop", // L'URL où envoyer la requête
                type: "POST", // Méthode de la requête (POST)
                data: order, // Les données à envoyer (l'ordre des éléments)
                success: function(response) { // Fonction exécutée en cas de succès de la requête
                    console.log(response); // Affichage de la réponse dans la console
                },
                error: function(xhr, status, error) { // Fonction exécutée en cas d'erreur de la requête
                    console.error(error); // Affichage de l'erreur dans la console
                }
            });
        }
    });

    // Activation de la fonctionnalité de tri pour la liste "unpinned"
    $("#unpinned").sortable({
        connectWith: "#pinned", // Permet de connecter cette liste avec la liste "pinned"
        update: function(event, ui) { // Cette fonction est appelée lorsque l'ordre des éléments est mis à jour
            // Sérialisation de l'ordre des éléments et ajout d'un paramètre de mise à jour
            var order = $(this).sortable("serialize") + '&update=update';

            // Envoi d'une requête AJAX pour mettre à jour l'ordre des éléments côté serveur
            $.ajax({
                url: "note/drag_and_drop", // L'URL où envoyer la requête
                type: "POST", // Méthode de la requête (POST)
                data: order, // Les données à envoyer (l'ordre des éléments)
                success: function(response) { // Fonction exécutée en cas de succès de la requête
                    console.log(response); // Affichage de la réponse dans la console
                }
            });
        } 
    });
});
