<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,200,0,-25">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="barre">
        <a class="back" href="note/open_note/<?= $note->note_id ?>"><span
                class="material-symbols-outlined">arrow_back_ios</span></a>
    </div>

    <div class="container box-shares">
        <h3>Labels :</h3>
        <?php if (count($labels) == 0): ?>
            <p class="share-empty" id="share-empty">This note does not yet have a label.</p>
        <?php else: ?>
            <span class="note_body_edit">Add a new label</span>
            <div class="note_body_checklist_edit">
                <?php foreach ($content as $row): ?>
                    <div class="edit_checklist_form">
                        <div class="edit_check_div">
                            <input class="check_square" type="checkbox" value="<?= $row["id"] ?>" name="box" <?= $row["checked"] ? 'checked' : '' ?> disabled>
                        </div>
                        <label class="checklist_elements <?= $row["checked"] ? 'check_label' : '' ?>">
                            <?= $row["content"] ?>
                        </label>
                        <input type="hidden" name="remove" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete" value="<?= $row["id"] ?>" class="icone-delete">-</button>
                    </div>
                <?php endforeach; ?>
                <label for="new">New item</label>
                <div class="edit_checklist_form">
                    <input type="text" class="form-control-edit" id="new" name="new">
                    <?php if (!empty($errors['items'])): ?>
                        <span class="text-danger erreur_edit"><?= $errors['items'] ?></span>
                    <?php endif; ?>
                    <button type="submit" class="icone-add">+</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>