$(document).ready(function () {
    // Récupération des éléments du DOM
    const checkboxes = document.querySelectorAll('.check-box-label');
    const notesContainer = document.getElementById('pinned');
    const buttonSearch = document.getElementById('searchButton');
    selectedLabels = [];

    $('#searchButton').hide();
    // Gestionnaire évènements :

    // Récupération des labels cochés sous forme de tableau
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            selectedLabels = Array.from(checkboxes)
                // map valeur du label avec checked(true/false)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            search(selectedLabels);
        });
    });

    function search(selectedLabels) {
        var requestData = {
            check: selectedLabels
        };

        $.ajax({
            url: "search/search_service",
            type: "POST",
            data: requestData,
            success: function (data) {
                // mise à jour affichage des notes
                notesContainer.innerHTML = "";
                console.log(data);
                if (data.length > 2 ) {
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
                } else
                    notesContainer.innerHTML = '<p class="title-empty">No such note</p>';
            },
            error: function (xhr, status, error) {
                console.error("Erreur lors de la vérification du contenu de la note : ", error);
            }
        });
    }

});