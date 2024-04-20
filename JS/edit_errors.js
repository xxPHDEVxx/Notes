// affiche message d'erreur dans la div concernée pour le titre
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
        
        if(errorMessage) {
            titleErrorDiv.style.display = 'block';
            titleErrorDiv.textContent = errorMessage;
            titleInput.classList.add('is-invalid');
        } else {
            titleErrorDiv.style.display = 'none';
            titleInput.classList.remove('is-invalid');
        }
    });
});