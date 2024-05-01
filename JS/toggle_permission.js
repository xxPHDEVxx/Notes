$(document).ready(function () {
    $('.form_toggle').submit(function (event) {
        event.preventDefault();

        var formData = $(this).serialize();
        console.log(formData);
    })
})