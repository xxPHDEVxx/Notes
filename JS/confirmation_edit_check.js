document.addEventListener('DOMContentLoaded', function () {
    // Récupération des éléments nécessaires
    const originalTitle = document.getElementById('title').value;
    const checklistDiv = document.querySelector('.note_body_checklist_edit');
    const backButton = document.querySelector('.back');
    const modal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
    const confirmExitButton = document.getElementById('confirmExitButton');

    // Stockage du nombre d'éléments de la checklist initial
    const originalItemCount = checklistDiv.children.length;

    // Observer pour détecter les changements dans la checklist
    const observer = new MutationObserver(function () {
        if (dataHasChanged(originalTitle)) {
            modal.show();
        }
    });

    // Configuration de l'observer
    const config = { childList: true };

    // Commence à observer la div checklist
    observer.observe(checklistDiv, config);

    // Vérifie si des données ont changé (titre ou nombre d'éléments dans la checklist)
    function dataHasChanged(originalTitle) {
        return originalTitle !== document.getElementById('title').value ||
            originalItemCount !== checklistDiv.children.length;
    }

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
});
