document.addEventListener('DOMContentLoaded', function () {
    const originalData = {
        title: document.getElementById('title').value,
        content: document.getElementById('content').value
    };

    const backButton = document.querySelector('.back');
    const modal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
    const confirmExitButton = document.getElementById('confirmExitButton');

    function dataHasChanged() {
        return originalData.title !== document.getElementById('title').value ||
            originalData.content !== document.getElementById('content').value;
    }

    backButton.addEventListener('click', function (event) {
        if (dataHasChanged()) {
            event.preventDefault();
            modal.show();
        }
    });

    confirmExitButton.addEventListener('click', function () {

        window.location.href = backButton.href;
    });
});
