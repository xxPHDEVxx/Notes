// Attendre que le DOM soit entièrement chargé avant d'exécuter le code
$(document).ready(function () {
    // Récupération des éléments du DOM
    const checkboxes = document.querySelectorAll('.check-box-label'); // Sélectionne toutes les cases à cocher ayant la classe 'check-box-label'
    const notesContainer = document.getElementById('pinned'); // Sélectionne l'élément avec l'ID 'pinned'
    const buttonSearch = document.getElementById('searchButton'); // Sélectionne le bouton avec l'ID 'searchButton'
    selectedLabels = []; // Initialise un tableau pour stocker les labels sélectionnés

    // Cache le bouton de recherche
    $('#searchButton').hide();

    // Gestionnaire d'événements : surveille les changements des cases à cocher
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            // Convertit NodeList en tableau et récupère les labels des cases cochées
            selectedLabels = Array.from(checkboxes)
                // Filtre les cases cochées
                .filter(cb => cb.checked)
                // Extrait la valeur des cases cochées
                .map(cb => cb.value);
            // Appelle la fonction de recherche avec les labels sélectionnés
            search(selectedLabels);
        });
    });

    // Fonction de recherche
    function search(selectedLabels) {
        // Crée un objet pour les données de la requête
        var requestData = {
            check: selectedLabels
        };

        // Envoie une requête AJAX au service de recherche
        $.ajax({
            url: "search/search_service", // URL du service de recherche
            type: "POST", // Type de requête HTTP
            data: requestData, // Données envoyées au serveur
            success: function (data) {
                // Efface le contenu précédent du conteneur de notes
                notesContainer.innerHTML = "";
                console.log(data); // Affiche les données reçues dans la console
                if (data.length > 2) {
                    // Parse les données JSON et ajoute chaque note au conteneur
                    JSON.parse(data).notes.forEach(note => {
                        notesContainer.innerHTML += `
                        <div class="note" id="note_${note.id}">
                            <p class="note-title">${note.title}</p>
                            <a class="link-note-archivee" href='note/open_note/${note.id}/${JSON.parse(data).notes_coded}/${JSON.parse(data).labels_checked_coded}'>
                                <div class="note-content">
                                    ${note.content ? `<div class="content_text">${note.content}</div>` : `
                                    <div class="content_check">
                                        <div class="check_item">
                                            ${note.content_checklist.map(item => `
                                                <div class="${item.checked ? 'checked_item' : 'unchecked_item'}">${item.content}</div>
                                            `).join('')}
                                        </div>
                                    </div>`}
                                </div>
                                <div class="box-label-note">
                                    ${note.labels.map(label => `<span class="badge label-note text-bg-secondary">${label}</span>`).join('')}
                                </div>
                            </a>
                        </div>
                    `;
                    })
                } else {
                    // Affiche un message si aucune note ne correspond à la recherche
                    notesContainer.innerHTML = '<p class="title-empty">No such note</p>';
                }
            },
            error: function (xhr, status, error) {
                // Gère les erreurs de la requête AJAX
                console.error("Erreur lors de la vérification du contenu de la note : ", error);
            }
        });
    }

});
