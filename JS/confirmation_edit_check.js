document.addEventListener('DOMContentLoaded', function () {
    // Récupération des éléments nécessaires
    const checklistItems = document.querySelectorAll('.checklist_elements');
    const backButton = document.querySelector('.back');
    const saveButton = document.querySelector('.save');
    const modal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
    const confirmExitButton = document.getElementById('confirmExitButton');
    let originalTitle = document.getElementById('title').value;
    let initialContents = [];
    let changed = false;
    let numberItems;

    saveData();

    // Événement du bouton retour
    backButton.addEventListener('click', function (event) {
        if (dataHasChanged(originalTitle)) {
            event.preventDefault();
            modal.show();
        }
    });


    // Événement du bouton de sauvegarde 
    saveButton.addEventListener('click', function () {
        numberItems = 0;
        saveData();
        originalTitle = document.getElementById('title').value;
        changed = false;
    });


    // Événement du bouton de confirmation pour quitter
    confirmExitButton.addEventListener('click', function () {
        modal.hide();
        window.location.href = backButton.getAttribute('href');
    });

    // sauvegarde des data avant changement pour afichage modal 
    function saveData() {
        checklistItems.forEach(function (itemContent) {
            itemContent = document.getElementById('item_content');
            // Récupération de l'ID de l'élément
            let name = itemContent.getAttribute('name');
            let itemIdMatch = name.match(/\[(\d+)\]/); // Extraction de l'ID
            let itemId = itemIdMatch[1];
            initialContents[itemId] = itemContent.value;
            numberItems++;
        });
    }

    // Vérifie si des données ont changé (titre ou nombre d'éléments dans la checklist)
    function dataHasChanged(originalTitle) {
        let changed = false;
        // Vérification du changement dans le titre
        if (titleChanged(originalTitle)) {
            changed = true;

        } else {
            // Parcourir chaque élément de checklist pour comparer les valeurs initiales
            checklistItems.forEach(function (itemContent) {
                itemContent = document.getElementById('item_content');
                // Récupération de l'ID de l'élément
                let name = itemContent.getAttribute('name');
                let itemIdMatch = name.match(/\[(\d+)\]/); // Extraction de l'ID
                let itemId = itemIdMatch[1];
                if (initialContents[itemId] !== itemContent.value) {
                    changed = true; // Si une différence est détectée, marquez la variable comme changée
                }
            });
        }

        return changed;
    }

    function titleChanged(originalTitle) {
        return (originalTitle !== document.getElementById('title').value);
    }
});



