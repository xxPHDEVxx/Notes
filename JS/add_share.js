$(document).ready(function () {
    $('#add_share').submit(function (event) {
        event.preventDefault();
        // Créer l'objet formData en conséquence
        var formData = {
            user: $(this).find('[name="user"]').val(),
            editor: $(this).find('[name="editor"]').val()
        };
        var note = $('#note').val();
        var user = $(this).find('[name="user"] option:selected').text();
        var user_id = $(this).find('[name="user"] option:selected').val();
        var editor = $(this).find('[name="editor"]').val();


        // Envoyer la requête AJAX au serveur
        $.ajax({
            type: 'POST',
            url: $('#add_share').attr('action'),
            data: formData,
            success: function (response) {
                // Mettre à jour l'affichage selon la réponse du serveur
                console.log('La requête a été envoyée avec succès.');

                $('#add_share').find('[name="user"] option:selected').detach();

                // Créer le formulaire
                var form = $('<form>').addClass('form_toggle').attr('action', 'note/toggle_permission/' + note).attr('method', 'post');

                // Créer div box-sharer pour insérer les input
                var boxSharer = $('<div>').addClass('box-sharer');

                // Créer les données cachées
                var inputNote = $('<input>').attr('type', 'text').attr('id', 'note').attr('name', 'note').val(note).hide();
                var inputShare = $('<input>').attr('type', 'text').attr('name', 'share').val(user_id).attr('id', 'share').hide();
                var inputEdit = $('<input>').attr('type', 'text').attr('name', 'edit').val(editor).attr('id', 'edit').hide();
                // Créer le paragraphe
                var paragraph = $('<p>').addClass('form-control-share form-share my-2').text(user);
                var roleSpan = $('<span>').attr('id', 'role').text((editor == 1) ? "(editor)" : "(reader)");
                paragraph.append(roleSpan);

                // Créer les boutons
                var buttonToggle = $('<button>').addClass('btn btn-primary btn-share btn-toggle').attr('type', 'submit').attr('name', 'action').val('toggle');
                var toggleIcon = $('<span>').addClass('material-symbols-outlined').text('change_circle');
                buttonToggle.append(toggleIcon);

                var buttonDelete = $('<button>').addClass('btn btn-danger btn-share btn-del').attr('type', 'submit').attr('name', 'action').val('delete');
                var deleteIcon = $('<span>').addClass('material-symbols-outlined').text('minimize');
                buttonDelete.append(deleteIcon);

                // Assembler les éléments
                boxSharer.append(inputNote).append(inputShare).append(inputEdit).append(paragraph).append(buttonToggle).append(buttonDelete);
                form.append(boxSharer);

                // Ajouter le formulaire à l'endroit désiré dans le DOM
                form.appendTo('.box-shares');


            },
            error: function (xhr, status, error) {
                console.error('Erreur lors de l\'envoi de la requête : ' + error);
            }
        });

        //verifier que le select est vide 
        var long = $('.us').children('option').length;

        console.log(long);
        if (long == 2) {
            $('.form-box-share').hide();
        }

    })
})