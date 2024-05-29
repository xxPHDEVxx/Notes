$(document).ready(function () {
    // Ces fonctions sont exécutées lorsque le DOM est complètement chargé

    function serializeList(list) {
        return list.sortable("toArray", { attribute: "id" });
    }
    // Fonction pour gérer la mise à jour de l'ordre des éléments
    function updateOrder(event, ui, sourceList, targetList) {
        // Sérialisation de l'ordre des éléments et ajout des paramètres de mise à jour

        let sourceItems = serializeList(sourceList);
        let targetItems = serializeList(targetList);
        let movedItemId = ui.item.attr('id').split('_')[1]; // Extrait l'ID de l'élément déplacé
        let order = sourceList.sortable("serialize") + '&update=update';
        
        order += '&source=' + sourceList.attr('id');
        order += '&target=' + targetList.attr('id');
        order += '&moved=' + movedItemId;
        order += '&sourceItems=' + sourceItems;
        order += '&targetItems=' + targetItems;

        // Envoi d'une requête AJAX pour mettre à jour l'ordre des éléments côté serveur
        $.ajax({
            url: "note/drag_and_drop", // L'URL où envoyer la requête
            type: "POST", // Méthode de la requête (POST)
            data: order, // Les données à envoyer (l'ordre des éléments)
            success: function (response) { // Fonction exécutée en cas de succès de la requête
                console.log(response); // Affichage de la réponse dans la console
                console.log(order);
            },
            error: function (xhr, status, error) { // Fonction exécutée en cas d'erreur de la requête
                console.error("Erreur lors de la mise à jour de l'ordre : ", error); // Affichage de l'erreur dans la console
            }
        });
    }

    // Activation de la fonctionnalité de tri pour la liste "pinned"
    $("#pinned, #unpinned").sortable({
        connectWith: "#unpinned, #pinned",
        start: function (event, ui) {
            sourceList = ui.item.parent();
        },
        update: function (event, ui) {
            targetList = ui.item.parent();
            updateOrder(event, ui, sourceList, targetList);
        }
    }).disableSelection();
});
