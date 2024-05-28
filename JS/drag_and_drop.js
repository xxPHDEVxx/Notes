$(document).ready(function() {
    // Cette fonction est exécutée lorsque le DOM est complètement chargé

    // Fonction pour gérer la mise à jour de l'ordre des éléments
    function updateOrder(event, ui, sourceList, targetList) {
        // Sérialisation de l'ordre des éléments et ajout des paramètres de mise à jour
        var order = sourceList.sortable("serialize") + '&update=update';
        order += '&source=' + sourceList.attr('id') + '&target=' + targetList.attr('id');
        var movedItemId = ui.item.attr('id').split('_')[1]; // Extrait l'ID de l'élément déplacé
        order += '&moved=' + movedItemId;

        // Envoi d'une requête AJAX pour mettre à jour l'ordre des éléments côté serveur
        $.ajax({
            url: "note/drag_and_drop", // L'URL où envoyer la requête
            type: "POST", // Méthode de la requête (POST)
            data: order, // Les données à envoyer (l'ordre des éléments)
            success: function(response) { // Fonction exécutée en cas de succès de la requête
                console.log(response); // Affichage de la réponse dans la console
                console.log(order);
            },
            error: function(xhr, status, error) { // Fonction exécutée en cas d'erreur de la requête
                console.error("Erreur lors de la mise à jour de l'ordre : ", error); // Affichage de l'erreur dans la console
            }
        });
    }

    // Activation de la fonctionnalité de tri pour la liste "pinned"
    $("#pinned").sortable({ 
        connectWith: "#unpinned", // Permet de connecter cette liste avec la liste "unpinned"
        update: function(event, ui) { // Cette fonction est appelée lorsque l'ordre des éléments est mis à jour
            var sourceList = ui.sender ? ui.sender : $(this);
            var targetList = $(this);
            updateOrder(event, ui, sourceList, targetList);
        }
    });

    // Activation de la fonctionnalité de tri pour la liste "unpinned"
    $("#unpinned").sortable({
        connectWith: "#pinned", // Permet de connecter cette liste avec la liste "pinned"
        update: function(event, ui) { // Cette fonction est appelée lorsque l'ordre des éléments est mis à jour
            var sourceList = ui.sender ? ui.sender : $(this);
            var targetList = $(this);
            updateOrder(event, ui, sourceList, targetList);
        }
    });
});
