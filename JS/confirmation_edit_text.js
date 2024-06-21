// Attendre que le DOM soit entièrement chargé avant d'exécuter le code
document.addEventListener('DOMContentLoaded', function () {
    // Stocke les données initiales du formulaire pour la comparaison ultérieure
    const originalData = {
        title: document.getElementById('title').value, // Récupère la valeur initiale du titre
        content: document.getElementById('content').value // Récupère la valeur initiale du contenu
    };

    // Sélectionne le bouton de retour en arrière
    const backButton = document.querySelector('.back');
    // Initialise le modal pour les changements non sauvegardés
    const modal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
    // Sélectionne le bouton de confirmation de sortie dans le modal
    const confirmExitButton = document.getElementById('confirmExitButton');

    // Fonction pour vérifier si les données du formulaire ont changé
    function dataHasChanged() {
        return originalData.title !== document.getElementById('title').value ||
            originalData.content !== document.getElementById('content').value;
    }

    // Ajoute un écouteur d'événements au bouton de retour
    backButton.addEventListener('click', function (event) {
        // Vérifie si les données ont changé
        if (dataHasChanged()) {
            event.preventDefault(); // Empêche l'action de retour par défaut
            modal.show(); // Affiche le modal pour avertir des changements non sauvegardés
        }
    });

    // Ajoute un écouteur d'événements au bouton de confirmation de sortie
    confirmExitButton.addEventListener('click', function () {
        // Redirige vers l'URL du bouton de retour
        window.location.href = backButton.href;
    });
});
