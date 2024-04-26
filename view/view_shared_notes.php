<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Shared by <?= $shared_by_name ?></title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php include("menu.php"); ?>
    <h1>Shared by <?= $shared_by_name ?></h1>

    <div class="shared_by">
        <?php if (count($shared_notes_as_editor) != 0) : ?>
            <div class="shared_title">Notes shared to you by <?= $shared_by_name ?> as editor</div>
            <div class="shared_editor">
                <?php foreach ($shared_notes_as_editor as $note_item) : ?>
                    <div class="note-archivee">
                        <a class="link-note-archivee" href="note/open_note/<?= $note_item["id"] ?>">
                            <?php include("note_in_list.php") ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (count($shared_notes_as_reader) != 0) : ?>
            <div class="shared_title">Notes shared to you by <?= $shared_by_name ?> as reader</div>
            <div class="shared_reader">
                <?php foreach ($shared_notes_as_reader as $note_item) : ?>
                    <div class="note-archivee">
                        <a class="link-note-archivee" href="note/open_note/<?= $note_item["id"] ?>">
                            <?php include("note_in_list.php") ?>
                        </a>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>



</body>

</html>