$(document).ready(function () {
    // Récupération des éléments du DOM
    const titleInput = document.getElementById('title');
    const titleErrorDiv = document.getElementById('titleError');
    const saveButton = document.getElementById('saveButton');
    const itemContents = document.querySelectorAll('.checklist_elements');

    // Désactiver le bouton de sauvegarde si le titre de la page n'est pas "Edit checklist note"
    if (!(document.title === "Edit checklist note"))
        saveButton.disabled = true;

    // Fonction pour mettre à jour l'état du bouton de sauvegarde en fonction de la validité du titre
    function updateSaveButtonState() {
        // Par défaut, le bouton est activé
        saveButton.disabled = false;

        // Parcours de tous les éléments de contenu
        for (let i = 0; i < itemContents.length; i++) {
            // Si un élément a la classe 'is-invalid', le bouton est désactivé et on quitte la boucle
            if (itemContents[i].classList.contains('is-invalid')) {
                saveButton.disabled = true;
                return; // Quitte la fonction immédiatement
            } else
                saveButton.disabled = false;
        }

        // Vérification pour le titre
        if (titleInput.classList.contains('is-invalid')) {
            saveButton.disabled = true;
        } else
            saveButton.disabled = false;

    }

    // Ajout des gestionnaires d'événements pour les éléments de contenu
    itemContents.forEach(function (itemContent) {
        itemContent.addEventListener('input', function () {
            // Récupération de l'ID de l'élément
            let name = itemContent.getAttribute('name');
            let itemIdMatch = name.match(/\[(\d+)\]/); // Extraction de l'ID
            let itemId = itemIdMatch[1];
            // Récupération de l'élément d'erreur correspondant
            let errorSpan = document.getElementById(`contentError_${itemId}`);
            // Vérification du contenu de l'élément
            checkContent(itemContent, itemId, errorSpan);
        });
    });

    // Ajout d'un gestionnaire d'événements pour le titre
    titleInput.addEventListener('input', checkTitle);

    // Fonction pour vérifier le contenu d'un élément
    function checkContent(itemInput, itemId, errorSpan) {
        // Envoi d'une requête AJAX pour vérifier si le contenu de la note est valide
        var requestData = {
            items: itemInput.value,
            id: itemId
        };

        $.ajax({
            url: "note/check_content_checklist_service", // L'URL où envoyer la requête
            type: "POST", // Méthode de la requête (POST)
            data: requestData, // Les données à envoyer (le titre de la note)
            success: function (data) { // Fonction exécutée en cas de succès de la requête
                console.log(data);
                if (data.length > 2) {
                    let message = JSON.parse(data)[itemId];
                    if (errorSpan) {
                        // Affichage de l'erreur
                        errorSpan.textContent = message;
                        errorSpan.style.display = 'block';
                        itemInput.classList.add('is-invalid');
                        itemInput.classList.remove('is-valid');
                    }
                } else {
                    if (errorSpan) {
                        // Masquage de l'erreur
                        errorSpan.style.display = 'none';
                        itemInput.classList.remove('is-invalid');
                        itemInput.classList.add('is-valid');
                    }
                }

                // Mise à jour de l'état du bouton de sauvegarde
                updateSaveButtonState();
            },
            error: function (xhr, status, error) { // Fonction exécutée en cas d'erreur de la requête
                console.error("Erreur lors de la vérification du contenu de la note : ", error); // Affichage de l'erreur dans la console
            }
        });
    }

    // Fonction pour vérifier le titre
    function checkTitle() {
        // Envoi d'une requête AJAX pour vérifier si le titre de la note est valide
        var requestData = {
            title: titleInput.value,
            note: note // Assurez-vous que note est défini
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
                    // Aucune erreur
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
