document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('content');  // Ajout du champ de contenu
    const titleErrorDiv = document.getElementsByClassName('text-danger');
    const contentErrorDiv = document.getElementsByClassName('text-danger');  // Div pour les erreurs de contenu

    // Désactiver le bouton save si une des classes 'is-invalid' est présente
    function updateSaveButtonState() {
        saveButton.disabled = titleInput.classList.contains('is-invalid') || contentInput.classList.contains('is-invalid');
    }

    titleInput.addEventListener('input', function () {
        const title = titleInput.value;
        let errorMessage = '';

        if (title.length < 3) {
            errorMessage = 'Le titre doit contenir au moins 3 caractères.';
        } else if (title.length > 25) {
            errorMessage = 'Le titre ne doit pas dépasser 25 caractères.';
        }

        if (errorMessage) {
            titleErrorDiv.textContent = errorMessage;
            titleErrorDiv.style.display = 'block';
            titleInput.classList.add('is-invalid');
            titleInput.classList.remove('is-valid');
        } else {
            //checkTitleUniqueness(title); soucis acces (403)
            titleErrorDiv.textContent = ""; //petit soucis nettoyage div
            contentErrorDiv.style.display = 'none';
            titleInput.classList.remove('is-invalid');
            titleInput.classList.add('is-valid');
        }
        updateSaveButtonState();
    });

    contentInput.addEventListener('input', function () {
        const content = contentInput.value;
        let errorMessage = '';

        if (content.length < 5 && content.length > 0) {
            errorMessage = 'Le contenu de la note doit contenir au moins 5 caractères.';
        } else if (content.length > 800) {
            errorMessage = 'Le contenu de la note ne doit pas dépasser 800 caractères.';
        }

        if (errorMessage) {
            contentErrorDiv.textContent = errorMessage;
            contentErrorDiv.style.display = 'block';
            contentInput.classList.add('is-invalid');
            contentInput.classList.remove('is-valid');
        } else {
            contentErrorDiv.style.display = 'none';
            contentInput.classList.remove('is-invalid');
            contentInput.classList.add('is-valid');
        }
        updateSaveButtonState();
    });

    function checkTitleUniqueness(title) {
        const encodedTitle = encodeURIComponent(title);
        const userId = document.getElementById('userId').value;  // Assurez-vous que cet élément existe
        console.log('Encoded title:', encodedTitle);
        console.log('Encoded user Id:', userId);

        fetch('model/Note.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `title=${encodedTitle}&userId=${encodeURIComponent(userId)}`
        })
        .then(response => {
            if (response.headers.get("content-type").includes("application/json")) {
                return response.json();
            } else {
                throw new Error('Réponse non-JSON reçue');
            }
        })
        .then(data => {
            if (data.isUnique === false) {
                titleErrorDiv.textContent = 'Une note avec ce titre existe déjà pour cet utilisateur.';
                titleErrorDiv.style.display = 'block';
                titleInput.classList.add('is-invalid');
            } else {
                titleErrorDiv.style.display = 'none';
                titleInput.classList.remove('is-invalid');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            titleErrorDiv.textContent = 'Erreur de vérification du titre: ' + error.message;
            titleErrorDiv.style.display = 'block';
        });
        updateSaveButtonState();
    }
});
