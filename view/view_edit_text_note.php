<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit note </title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0">
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php
    if ($labels_checked_coded != "") {
        $param = "$note->note_id/$notes_coded/$labels_checked_coded";
    } else {
        $param = "$note->note_id";
    }
    ?>

    <form method="post" class="edit_text_note_form" action="note/save_edit_text_note/<?= $param ?>">
        <div class="edit">
            <?php
            if ($labels_checked_coded != "") {
                $href = "note/open_note/$note_id/$notes_coded/$labels_checked_coded";
            } else {
                $href = "note/open_note/$note_id";
            }
            ?>
            <a class="back" href="<?= $href ?>"><span class="material-symbols-outlined">arrow_back_ios</span></a>
            <button class="save" type="submit" id="saveButton"><span
                    class="material-symbols-outlined">save</span></button>
        </div>
        <div class="edit_text_note_date">Created
            <?= $created ?><?= ($edited ? " Edited " . $edited : " Not edited yet") ?>
        </div>
        <div>
            <div class="text_note_form_items">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control title_add" value="<?= $note->title ?>">
                <span id="titleError" class="text-danger" style="display: none;"></span>
                <?php if (!empty($title_errors)): ?>
                    <?php foreach ($title_errors as $error): ?>
                        <span class="text-danger">
                            <p><?= $error ?></p>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="text_note_form_items">
                <label for="content">Text</label>
                <textarea class="form-control title_add" id="content" name="content"><?= $content ?></textarea>
                <span id="contentError" class="text-danger" style="display: none;"></span>
                <?php if (!empty($content_errors)): ?>
                    <?php foreach ($content_errors as $error): ?>
                        <span class="text-danger">
                            <p><?= $error ?></p>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
                <!-- view_error.php -->
                <?php include ("view_error.php"); ?>
            </div>
        </div>
    </form>

    <?php include ("view_modal.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>var note = <?= json_encode($note_id) ?>;</script>
    <script src="JS/edit_text_note_errors.js"></script>
    <script src="JS/confirmation_edit_text.js"></script>
</body>

</html>