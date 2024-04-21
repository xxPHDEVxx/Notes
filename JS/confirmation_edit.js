document.addEventListener('DOMContentLoaded', function() {
    const originalData = {
        title: document.getElementById('title').value,
        content: document.getElementById('content').value
    };

    const backButton = document.querySelector('.back'); // Remplacer par le sélecteur correct de votre icône
    const modal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
    const confirmExitButton = document.getElementById('confirmExitButton');

    function dataHasChanged() {
        return originalData.title !== document.getElementById('title').value ||
               originalData.content !== document.getElementById('content').value;
    }

    backButton.addEventListener('click', function(event) {
        if (dataHasChanged()) {
            event.preventDefault(); // Empêcher la navigation
            modal.show(); // Afficher la modal
        }
    });

    confirmExitButton.addEventListener('click', function() {
        // L'utilisateur confirme vouloir quitter, continuez la navigation
        window.location.href = backButton.href; // Assurez-vous que l'attribut href de backButton est correctement défini
    });
});
