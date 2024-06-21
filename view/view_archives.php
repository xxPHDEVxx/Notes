<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>My archives</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">

    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php include("menu.php"); ?>
    <h1 class="page-title">My archives</h1>
    <h2 class="page-subtitle">Archives</h2>
    <div class="my-archives">
        <?php if (count($archives) != 0) : ?>

            <?php foreach ($archives as $note_item) : ?>
                <div class="note-archivee">
                    <a class="link-note-archivee" href="note/open_note/<?= $note_item["id"] ?>">
                        <?php include("note_in_list.php") ?>
                        <div class="box-label-note">
                            <?php $note = Note::get_note_by_id($note_item["id"]);
                            foreach ($note->get_labels() as $label) : ?>
                                <span class="badge label-note text-bg-secondary"><?= $label ?></span>

                            <?php endforeach; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</body>

</html>