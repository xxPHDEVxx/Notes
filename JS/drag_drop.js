$(document).ready(function() {
    $("#pinned").sortable({
        connectWith: "#unpinned",
        update: function(event, ui) {
            var order = $(this).sortable("serialize") + '&update=update';
            $.ajax({
                url: "note/drag_and_drop",
                type: "POST",
                data: order,
                success: function(response) {
                    console.log(response);

                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });
    $("#unpinned").sortable({
        connectWith: "#pinned",
        update: function(event, ui) {
            var order = $(this).sortable("serialize") + '&update=update';
            $.ajax({
                url: "note/drag_and_drop",
                type: "POST",
                data: order,
                success: function(response) {
                    console.log(response);
                }
            });
        }
    });

});
