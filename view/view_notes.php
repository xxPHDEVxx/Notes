<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Keep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php include('view/menu.php'); ?>
    <h1>My notes</h1>
    <h2>Notes</h2> 
    <div class="view_notes">

        <?php if (count($notes) != 0) : ?>
            <?php foreach ($notes as $archive) : ?>
                <div class="note-archivee">
                    <div class="note-title"><?= $archive->title; ?></div>
                    <a class="link-note-archivee" href="#">
                        <div class="note-content">

                            <?php if ($archive->get_type() == "TextNote") : ?>
                                <div class="content_text">

                                    <?= $archive->content ?>

                                </div>

                            <?php else : ?>

                                <div class="content_check">
                                    <div class="check_item">
                                        <?php foreach ($archive->get_items() as $checklist_item) : ?>

                                            <?php if (!$checklist_item->checked) : ?>
                                                <div class="unchecked_item"><?= $checklist_item->content ?></div>
                                            <?php else : ?>
                                                <div class="checked_item"><?= $checklist_item->content ?></div>
                                            <?php endif; ?>

                                        <?php endforeach; ?>

                                    </div>
                                </div>

                            <?php endif; ?>


                        </div>

                    </a>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>

</html>