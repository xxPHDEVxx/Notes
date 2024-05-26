<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open "<?=$note->title?>"</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body>
    <div class="barre">

        <a class="back" href="note/index"><span class="material-symbols-outlined">arrow_back_ios</span></a>
        <?php if ($archived == 1) : ?>
            <form action="note/delete_note/<?= $note_id ?>" id="deleteForm" method="post">
                <button class="delete" type="submit" id="delete_icon"><span class="material-symbols-outlined">delete_forever</span></button>
            </form>
            <a class="unarchive" href="note/unarchive/<?= $note_id ?>"><span class="material-symbols-outlined">unarchive</span></a>


        <?php elseif ($is_shared_as_editor == 1) : ?>
            <a class="isShared" href="note/edit_checklist<?= $note_id ?>"><span class="material-symbols-outlined">edit</span></a>
        <?php elseif ($archived == 0 && $is_shared_as_editor == 0 && $is_shared_as_reader == 0) : ?>
            <a class="share" href="note/shares/<?= $note_id ?>"><span class="material-symbols-outlined">share</span></a>
            <?php if ($pinned) : ?>
                <a class="pinned" href="note/unpin/<?= $note_id ?>"><span class="material-symbols-rounded">push_pin</span></a>
                <?php else : ?>
                    <a class="pinned" href="note/pin/<?= $note_id ?>"><span class="material-symbols-outlined">push_pin</span></a>
                <?php endif; ?>
                <a class="archive" href="note/archive/<?= $note_id ?>"><span class="material-symbols-outlined">archive</span></a>
                <a class="isShared" href="note/edit/<?= $note_id ?>"><span class="material-symbols-outlined">edit</span></a>
            <?php endif; ?>

    </div>
    <div class="dates">Created <?= $created ?><?= ($edited ? " Edited " . $edited : " Not edited yet") ?></div>
    <div class="title_note_title">Title</div>
    <div class="title_note"> <?= $note->title ?></div>
    <div class="note_body_title">Items</div>

    <div class="note_body_checklist">


        <?php foreach ($note_body as $row) : ?>
            <?php if ($row['checked']) : ?>
                <form class="check_form" action="note/update_checked" method="post">
                    <input type="text" name="uncheck" value="<?= $row["id"] ?>" class="item" hidden>
                    <input class="material-symbols-outlined check_submit " type="submit" value='check_box' id="uncheck<?= $row["id"] ?>">
                    <label class="check_label item_label" for="uncheck<?= $row["id"] ?>" id="<?= $row["id"] ?>"> <?= $row["content"] ?></label>
                </form>
            <?php else : ?>
                <form class="check_form" method="post">
                    <input type="text" name="check" value="<?= $row["id"] ?>" class="item" hidden>
                    <input class="material-symbols-outlined check_submit" type="submit" value="check_box_outline_blank" id="check<?= $row["id"] ?>">
                    <label class="uncheck_label item_label" for="check<?= $row["id"] ?>" id="<?= $row["id"] ?>"> <?= $row["content"] ?></label>
                </form>
            <?php endif; ?>

        <?php endforeach; ?>





    </div>
    <?php include("view/view_modal_delete.php"); ?>
    <script src="JS/check_uncheck.js"></script>
    <script src="JS/confirmation_delete.js"></script>
</body>

</html>