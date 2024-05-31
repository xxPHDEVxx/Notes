$(document).ready(function () {
    // Récupération des éléments du DOM
    const titleInput = document.getElementById('title');
    const titleErrorDiv = document.getElementById('titleError');
    const saveButton = document.getElementById('saveButton');
    const itemContents = document.querySelectorAll('.checklist_elements');
    const newItemTag = document.getElementById('new');

    // Désactiver le bouton de sauvegarde si le titre de la page n'est pas "Edit checklist note"
    if (!(document.title === "Edit checklist note"))
        saveButton.disabled = true;


    /* ******Gestionnaire d'évènements****** */

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

    // Ajout d'un gestionnaire d'évènements pour les new items
    newItemTag.addEventListener('input', function () {
        checkNewContent(newItemTag);
    });

    // gestionnaire pour ajout d'item
    $('.icone-add').click(function (event) {
        event.preventDefault(); // Empêche le comportement par défaut du formulaire (soumission)
        addNewContent(newItemTag);
    });

    // gestionnaire pour supression d'item
    $('.icone-delete').click(function (event) {
        event.preventDefault(); // Empêche le comportement par défaut du formulaire (soumission)

        // Récupération de l'ID de l'élément
        let buttonId = this.id;
        let itemIdMatch = buttonId.match(/delete(\d+)/); // Extraction de l'ID
        if (itemIdMatch) {
            id = itemIdMatch[1];
            deleteContent(id); // Appelle la fonction pour supprimer l'élément
        }
    });

    $('#saveButton').click(function (event) {
        event.preventDefault(); // Empêche le comportement par défaut du formulaire (soumission)
        addTitle();

        // Parcours de tous les éléments de contenu
        itemContents.forEach(function (itemContent) {
            // Récupération de l'ID de l'élément
            let name = itemContent.getAttribute('name');
            let itemIdMatch = name.match(/\[(\d+)\]/); // Extraction de l'ID
            let itemId = itemIdMatch[1];
            // Récupération de l'élément d'erreur correspondant
            // Ajout du contenu de l'élément
            saveContent(itemContent, itemId);
        });
    });



    /* *****Méthodes***** */

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

        // Vérification pour new item
        if (newItemTag.classList.contains('is-invalid')) {
            saveButton.disabled = true;
        } else
            saveButton.disabled = false;

    }

    function isDuplicateContent(content, itemId) {
        const checklistItems = document.querySelectorAll('.checklist_elements');
        let isDuplicate = false;
        for (let i = 0; i < checklistItems.length; i++) {
            let itemContent = checklistItems[i];
            // Récupération de l'ID de l'élément
            let name = itemContent.getAttribute('name');
            let itemIdMatch = name.match(/\[(\d+)\]/); // Extraction de l'ID
            let existingItemId = itemIdMatch[1];
            if (existingItemId != itemId && itemContent.value == content) {
                isDuplicate = true;
                break;
            }
        }
        return isDuplicate;
    }


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
                        if (isDuplicateContent(itemInput.value, itemId)) {
                            // Affichage de l'erreur
                            errorSpan.textContent = "it must be unique";
                            errorSpan.style.display = 'block';
                            itemInput.classList.add('is-invalid');
                            itemInput.classList.remove('is-valid');
                        } else {
                            // Masquage de l'erreur
                            errorSpan.style.display = 'none';
                            itemInput.classList.remove('is-invalid');
                            itemInput.classList.add('is-valid');
                        }
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

    function saveContent(itemInput, itemId) {
        // Envoi d'une requête AJAX pour vérifier si le contenu de la note est valide
        var requestData = {
            items: itemInput.value,
            id: itemId
        };

        $.ajax({
            url: "note/save_content_checklist_service", // L'URL où envoyer la requête
            type: "POST", // Méthode de la requête (POST)
            data: requestData, // Les données à envoyer (le titre de la note)
            success: function (data) { // Fonction exécutée en cas de succès de la requête
                console.log(data);
            },
            error: function (xhr, status, error) { // Fonction exécutée en cas d'erreur de la requête
                console.error("Erreur lors de l'ajout du contenu de la note : ", error); // Affichage de l'erreur dans la console
            }
        });
    }

    function checkNewContent(newItem) {
        // Envoi d'une requête AJAX pour vérifier si le contenu de la note est valide
        var requestData = {
            new: newItem.value,
            note_id: note
        };

        let errorSpan = document.getElementById(`newContentError`);

        $.ajax({
            url: "note/check_new_content_checklist_service", // L'URL où envoyer la requête
            type: "POST", // Méthode de la requête (POST)
            data: requestData, // Les données à envoyer (le titre de la note)
            success: function (data) { // Fonction exécutée en cas de succès de la requête
                if (data.length > 2) {
                    let message = JSON.parse(data)[0];
                    if (errorSpan) {
                        // Affichage de l'erreur
                        errorSpan.textContent = message;
                        errorSpan.style.display = 'block';
                        newItem.classList.add('is-invalid');
                        newItem.classList.remove('is-valid');
                    }
                } else {
                    if (errorSpan) {
                        // Masquage de l'erreur
                        errorSpan.style.display = 'none';
                        newItem.classList.remove('is-invalid');
                        newItem.classList.add('is-valid');
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

    function addNewContent(newItem) {
        // Envoi d'une requête AJAX pour vérifier si le contenu de la note est valide
        var requestData = {
            new: newItem.value,
            note_id: note
        };

        $.ajax({
            url: "note/add_new_content_checklist_service", // L'URL où envoyer la requête
            type: "POST", // Méthode de la requête (POST)
            data: requestData, // Les données à envoyer (le titre de la note)
            success: function (data) {
                let item = JSON.parse(data);
                console.log(item);
                let editChecklistItem = createEditChecklistItem(item.id, item.content, item.checked);
                document.getElementById("container-item").appendChild(editChecklistItem);
            },
            error: function (xhr, status, error) { // Fonction exécutée en cas d'erreur de la requête
                console.error("Erreur lors de la vérification du contenu de la note : ", error); // Affichage de l'erreur dans la console
            }
        });
    }

    function deleteContent(itemId) {
        // Envoi d'une requête AJAX pour vérifier si le contenu de la note est valide
        var requestData = {
            id: itemId
        };

        $.ajax({
            url: "note/delete_item_service", // L'URL où envoyer la requête
            type: "POST", // Méthode de la requête (POST)
            data: requestData, // Les données à envoyer (le titre de la note)
            success: function (data) { // Fonction exécutée en cas de succès de la requête
                // Suppression du div contenant l'élément après la suppression réussie
                $('#div' + itemId).remove();
                console.log("Item successfully deleted.");
            },
            error: function (xhr, status, error) { // Fonction exécutée en cas d'erreur de la requête
                console.error("Erreur lors de l'ajout du contenu de la note : ", error); // Affichage de l'erreur dans la console
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

    // Fonction pour vérifier le titre
    function addTitle() {
        // Envoi d'une requête AJAX pour vérifier si le titre de la note est valide
        var requestData = {
            title: titleInput.value,
            note: note // Assurez-vous que note est défini
        };

        $.ajax({
            url: "note/add_title_service", // L'URL où envoyer la requête
            type: "POST", // Méthode de la requête (POST)
            data: requestData, // Les données à envoyer (le titre de la note)
            success: function () { // Fonction exécutée en cas de succès de la requête

            },
            error: function (xhr, status, error) { // Fonction exécutée en cas d'erreur de la requête
                console.error("Erreur lors de la vérification du titre unique : ", error); // Affichage de l'erreur dans la console
            }
        });
    }

    function createEditChecklistItem(id, content, checked) {
        // Création du conteneur principal
        let divContainer = document.createElement('div');
        divContainer.classList.add('edit_checklist_form');
        divContainer.id = 'div' + id;

        // Création de la div pour la case à cocher
        let divCheckDiv = document.createElement('div');
        divCheckDiv.classList.add('edit_check_div');

        // Création de la case à cocher
        let checkbox = document.createElement('input');
        checkbox.classList.add('check_square');
        checkbox.type = 'checkbox';
        checkbox.value = id;
        checkbox.name = 'box';
        if (checked) {
            checkbox.checked = true;
        }
        checkbox.disabled = true;

        // Création de l'élément input pour le contenu de l'item
        let inputContent = document.createElement('input');
        inputContent.type = 'text';
        inputContent.name = 'items[' + id + ']';
        inputContent.classList.add('checklist_elements');
        if (checked) {
            inputContent.classList.add('check_label');
        }
        inputContent.id = 'item_content_' + id;
        inputContent.value = content;

        // Création de l'input caché pour le retrait de l'élément
        let inputRemove = document.createElement('input');
        inputRemove.type = 'hidden';
        inputRemove.name = 'remove';
        inputRemove.value = id;

        // Création du bouton de suppression
        let deleteButton = document.createElement('button');
        deleteButton.type = 'submit';
        deleteButton.id = 'delete' + id;
        deleteButton.name = 'delete';
        deleteButton.value = id;
        deleteButton.classList.add('icone-delete');
        deleteButton.textContent = '-';

        // Ajout des éléments créés au conteneur principal
        divContainer.appendChild(divCheckDiv);
        divCheckDiv.appendChild(checkbox);
        divContainer.appendChild(inputContent);
        divContainer.appendChild(inputRemove);
        divContainer.appendChild(deleteButton);

        // Retourner le conteneur principal
        return divContainer;
    }
});
