<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Search</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,200,0,-25">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0">
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <?php include('view/menu.php'); ?>
    <div class="view_notes_header">
        <h1>Search my notes</h1>
    </div>
    <div class="box-search">
        <div class="form-check">
            <p>Search notes by tag : </p>
            <?php foreach ($labels as $label) : ?>
                <label for="<?= $label ?>" class="radio-label">
                    <input type="checkbox" id="<?= $label ?>" name="<?= $label ?>" value="<?= $label ?>" class="check-box-label">
                    <span class="custom-checkbox-label"></span>
                    <?= $label ?>
                </label>

            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>