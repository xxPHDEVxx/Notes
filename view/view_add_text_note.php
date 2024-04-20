<!DOCTYPE html>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Note</title>
    <base href="<?= $web_root ?>" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <script src="JS/edit_errors.js" ></script>
</head>
<body>
    <form method="post" action="note/save_add_text_note">
        <div class="edit">
            <a class="back" href="<?= $_SESSION['previous_page'] ?>"><span class="material-symbols-outlined">arrow_back_ios</span></a>
            <button class="save" type="submit"><span class="material-symbols-outlined">save</span></button>
        </div>
        <div class="dates">Today's Date: <?= date("Y-m-d H:i:s") ?></div>
        <label for="title" class="title_note_title">Title</label>
        <input type="text" class="title_edit_note" id="title" name="title" placeholder="Enter note title">
        <div id="titleError" class="invalid-feedback" style="display: none;"></div>

        <label for="content" class="note_body_title">Text</label>
        <textarea class="note_body_text" id="content" name="content" placeholder="Type your note here..."></textarea>
    </form>
</body>
</html>

