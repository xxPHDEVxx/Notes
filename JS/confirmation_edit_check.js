document.addEventListener('DOMContentLoaded', function () {
    let isModified = false;

    const originalData = collectOriginalData();
    const backButton = document.querySelector('.back');
    const modal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'));
    const confirmExitButton = document.getElementById('confirmExitButton');  


    document.querySelectorAll('.icone-add, .icone-delete').forEach(button => {
        button.addEventListener('click', function() {
            isModified = true;
        });
    });

    backButton.addEventListener('click', function(event) {
        if (isModified || dataHasChanged(originalData)) {
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
        const currentData = {
            title: document.getElementById('title').value,
            items: Array.from(document.querySelectorAll('.form-control-edit')).map(input => input.value)
        };
        return original.title !== currentData.title || 
               !original.items.every((value, index) => value === currentData.items[index]);
    }
});
