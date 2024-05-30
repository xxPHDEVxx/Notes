$(document).ready(function () {
    const titleInput = document.getElementById('title');
    const titleErrorDiv = document.getElementById('titleError');
    const saveButton = document.getElementById('saveButton');
    if(!(document.title === "Edit checklist note"))
        saveButton.disabled = true;

    // Désactiver le bouton save si une des classes 'is-invalid' est présente
    function updateSaveButtonState() {
        if (titleInput.classList.contains('is-valid'))
             { saveButton.disabled = false; }
        else
            saveButton.disabled = true;
    }

    titleInput.addEventListener('input', checkTitle);

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