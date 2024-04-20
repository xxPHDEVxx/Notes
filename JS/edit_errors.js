document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.getElementById('title');
    const titleErrorDiv = document.getElementById('titleError');

    titleInput.addEventListener('input', function () {
        const title = titleInput.value;
        let errorMessage = '';

        if (title.length < 3) {
            errorMessage = 'Le titre doit contenir au moins 3 caractères.';
        } else if (title.length > 25) {
            errorMessage = 'Le titre ne doit pas dépasser 25 caractères.';
        }

        if (!errorMessage) {

            const encodedTitle = encodeURIComponent(title);
            const encodedUserId = encodeURIComponent(userId);
            console.log('Encoded title:', encodedTitle);
            console.log('Encoded user Id:', encodedUserId);
            // Si la longueur du titre est valide, vérifiez son unicité
            fetch('model/Note/validate_title.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `title=${encodeURIComponent(title)}&userId=${encodeURIComponent(userId)}`
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
            
        } else {
            titleErrorDiv.textContent = errorMessage;
            titleErrorDiv.style.display = 'block';
            titleInput.classList.add('is-invalid');
        }
    });
});
