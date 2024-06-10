<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,200,0,-25">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="barre">
        <a class="back" href="note/open_note/<?= $note->note_id ?>"><span class="material-symbols-outlined">arrow_back_ios</span></a>
    </div>

    <div class="container box-shares">
        <h3>Labels :</h3>
        <?php if (count($labels) == 0) : ?>
            <p class="share-empty" id="share-empty">This note does not yet have a label.</p>
        <?php else : ?>
            <?php foreach ($labels as $label) : ?>
                <form action="note/delete_label/<?= $note->note_id ?>" class="form_toggle" method="post">
                    <div class="box-label">
                        <input type="text" id="note" name="label" value="<?= $label ?>" class="form-control-label my-1" disabled>
                        <button class="btn btn-danger btn-share btn-del" type="submit" name="label" value="<?= $label ?>"><span class="material-symbols-outlined">
                                    minimize
                                </span></button>
                    </div>
                </form>
            <?php endforeach; ?>
        <?php endif; ?>
        <form action="note/labels/<?= $note->note_id ?>" method="post">
            <label for="new-label">Add a new label:</label>
            <input list="new-label-list" id="new-label" name="new_label" class="form-control-label" placeholder="Type to search or create..." value="<?= isset($_POST['new_label']) ? htmlspecialchars($_POST['new_label']) : "" ?>">
            <button class="btn btn-primary btn-share" type="submit">
                    <span class="material-symbols-outlined">add</span></button>
            <?php if (!empty($errors)) : ?>
                <?php foreach ($errors as $error) : ?>
                    <p class="text-danger"><?= $error ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
            <datalist id="new-label-list">
                <?php foreach ($all as $lab) : ?>
                    <option value="<?= $lab ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </form>
    </div>
</body>

</html>