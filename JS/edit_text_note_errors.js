$(document).ready(function () {
    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('content');  // Ajout du champ de contenu
    const titleErrorDiv = document.getElementById('titleError');
    const contentErrorDiv = document.getElementById('contentError');  // Div pour les erreurs de contenu
    const saveButton = document.getElementById('saveButton');
    if(!(document.title === "Edit note"))
        saveButton.disabled = true;

    // Désactiver le bouton save si une des classes 'is-invalid' est présente
    function updateSaveButtonState() {
        if (titleInput.classList.contains('is-valid')
            && !(contentInput.classList.contains('is-invalid'))) { saveButton.disabled = false; }
        else
            saveButton.disabled = true;
    }

    titleInput.addEventListener('input', checkTitle);

    contentInput.addEventListener('input', checkContent);

    function checkContent() {
        let requestData = {
            content: contentInput.value,
        };

        $.ajax({
            url: "note/check_content_service", // L'URL où envoyer la requête
            type: "POST", // Méthode de la requête (POST)
            data: requestData, // Les données à envoyer (le titre de la note)
            success: function (data) { // Fonction exécutée en cas de succès de la requête
                if (data != "") {
                    contentErrorDiv.textContent = data;
                    contentErrorDiv.style.display = 'block';
                    contentInput.classList.add('is-invalid');
                    contentInput.classList.remove('is-valid');
                    updateSaveButtonState();
                } else {
                    contentErrorDiv.style.display = 'none';
                    contentInput.classList.remove('is-invalid');
                    contentInput.classList.add('is-valid');
                    updateSaveButtonState();
                }
            },
            error: function (xhr, status, error) { // Fonction exécutée en cas d'erreur de la requête
                console.error("Erreur lors de la vérification du titre unique : ", error); // Affichage de l'erreur dans la console
            }
        });

    }

    function checkTitle() {
        // Envoi d'une requête AJAX pour vérifier si le titre de la note est unique

        // Données à envoyer dans la requête A
        var requestData = {
            title: titleInput.value,
            note: note
        };

        $.ajax({
            url: "note/check_title_service", // L'URL où envoyer la requête
            type: "POST", // Méthode de la requête (POST)
            data: requestData, // Les données à envoyer (le titre de la note)
            success: function (data) { // Fonction exécutée en cas de succès de la requête
                if (data != "") {
                    // Cas où il y a une erreur
                    titleErrorDiv.textContent = data;
                    titleErrorDiv.style.display = 'block';
                    titleInput.classList.add('is-invalid');
                    titleInput.classList.remove('is-valid');
                    updateSaveButtonState();
                } else {
                    titleErrorDiv.style.display = 'none';
                    titleInput.classList.remove('is-invalid');
                    titleInput.classList.add('is-valid');
                    updateSaveButtonState();
                }
            },
            error: function (xhr, status, error) { // Fonction exécutée en cas d'erreur de la requête
                console.error("Erreur lors de la vérification du titre unique : ", error); // Affichage de l'erreur dans la console
            }
        });
    }
});