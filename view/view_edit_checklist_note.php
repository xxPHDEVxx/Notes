<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Checklist_Note</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />

</head>

<body>
    <form method="post" action="note/edit_checklist_note/<?= $note_id ?>">
        <?php include("edit_note.php"); ?>
         <label for="items" class="note_body_title">Items</label>
        <div class="note_body_checklist">
            <?php foreach ($note_body as $row) : ?>
                <div class="edit_checklist_form">
                    <div class="edit_check_div">
                        <input class="check_square" type="checkbox" value="<?= $row["id"] ?>" id='<?= $row["id"] ?>' name="box" <?= $row["checked"] ? 'checked' : '' ?> disabled>
                    </div>
                    <label class="checklist_elements <?= $row["checked"] ? 'check_label' : '' ?>" id='<?= $row["id"] ?>'>
                        <?= $row["content"] ?>
                    </label>
                    <input type="hidden" name="remove" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete" value="<?= $row["id"] ?>" id="<?= $row['id'] ?>" class="icone-delete">-</button>
                </div>
            <?php endforeach; ?>
            <label for="new">New item</label>
            <div class="edit_checklist_form">
                <input type="text" class="form-control-edit" id="new" name="new">
                <button type="submit" class="icone-add">+</button>
            </div>
        </div>
    </form>
    </div>
</body>

</html>