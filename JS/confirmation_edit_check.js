document.addEventListener('DOMContentLoaded', function () {
    const originalData = collectOriginalData();
    const backButton = document.querySelector('.back');
    const modal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
    const confirmExitButton = document.getElementById('confirmExitButton');

    backButton.addEventListener('click', function(event) {
        if (dataHasChanged(originalData)) {
            event.preventDefault(); 
            modal.show(); 
        }
    });

    confirmExitButton.addEventListener('click', function() {
        modal.hide(); 
        window.location.href = backButton.getAttribute('href');
    });

    function collectOriginalData() {
        return {
            title: document.getElementById('title').value,
            items: Array.from(document.querySelectorAll('.form-control-edit')).map(input => input.value)
        };
    }

    function dataHasChanged(original) {
        const currentNumItems = document.querySelectorAll('.form-control-edit').length;
        return original.title !== document.getElementById('title').value || 
               original.numItems !== currentNumItems;
    }
});
