document.addEventListener('DOMContentLoaded', function () {
    // Récupération des éléments nécessaires
    const originalTitle = document.getElementById('title').value;
    const checklistItems = document.querySelectorAll('.checklist_elements');
    const checklistDiv = document.querySelector('.note_body_checklist_edit');
    const backButton = document.querySelector('.back');
    const modal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
    const confirmExitButton = document.getElementById('confirmExitButton');
    let initialContents = [];

    // Parcourir chaque élément de checklist pour stocker les valeurs initiales
    checklistItems.forEach(function (itemContent) {
        itemContent = document.getElementById('item_content');
        // Récupération de l'ID de l'élément
        let name = itemContent.getAttribute('name');
        let itemIdMatch = name.match(/\[(\d+)\]/); // Extraction de l'ID
        let itemId = itemIdMatch[1];
        initialContents[itemId] = itemContent.value;
    });

    console.log(initialContents);

    // Événement du bouton retour
    backButton.addEventListener('click', function (event) {
        if (dataHasChanged(originalTitle)) {
            event.preventDefault();
            modal.show();
        }
    });

    // Événement du bouton de confirmation pour quitter
    confirmExitButton.addEventListener('click', function () {
        modal.hide();
        window.location.href = backButton.getAttribute('href');
    });

    // Vérifie si des données ont changé (titre ou nombre d'éléments dans la checklist)
    function dataHasChanged(originalTitle) {
        let changed = false;
        // Parcourir chaque élément de checklist pour stocker les valeurs initiales
        checklistItems.forEach(function (itemContent) {
            itemContent = document.getElementById('item_content');
            // Récupération de l'ID de l'élément
            let name = itemContent.getAttribute('name');
            let itemIdMatch = name.match(/\[(\d+)\]/); // Extraction de l'ID
            let itemId = itemIdMatch[1];
            if (initialContents[itemId] != itemContent.value)
                changed = true;
        });
        if (originalTitle !== document.getElementById('title').value)
            changed = true;
        return changed;
    }
});
