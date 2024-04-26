$(document).ready(function () {

    $('.check_form').submit(function (event) {
        // Empêcher l'envoi du formulaire par défaut
        event.preventDefault();
        // Récupérer les données du formulaire
        var formData = $(this).serialize();
        // Envoyer la requête AJAX au serveur
        $.ajax({
            type: 'POST',
            url: "note/update_checked",
            data: formData,
            success: function (response) {
                // Mettre à jour l'affichage selon la réponse du serveur
                console.log('La requête a été envoyée avec succès.');
                // Actualiser l'interface utilisateur en fonction de la réponse
                // Par exemple, vous pouvez mettre à jour le style ou le contenu de l'élément modifié

                var currentValue = $(event.target).find('.check_submit');
                // Récupérer le label associé à l'élément bouton cliqué
                var label = $(currentValue).siblings('label');

                console.log(label);

                if (currentValue.val() === "check_box") {
                    //changement de la checkbox et de la label barrée
                    currentValue.val("check_box_outline_blank");
                    //changer le nom de la class pour la rendre barrée ou non 
                    label.removeClass('check_label').addClass('uncheck_label');
                } else {
                    currentValue.val("check_box");
                    label.removeClass('uncheck_label').addClass('check_label');
                }

            },
            error: function (xhr, status, error) {
                console.error('Erreur lors de l\'envoi de la requête : ' + error);
            }
        });
    });
});




