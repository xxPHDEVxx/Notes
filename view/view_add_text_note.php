<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a new note</title>
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
    <form method="post" action="note/save_add_text_note">
        <div class="add_header">
            <a class="back" href="note/index"><span
                    class="material-symbols-outlined">arrow_back_ios</span></a>
            <button class="save" type="submit"><span class="material-symbols-outlined">save</span></button>
        </div>
        <div class="dates">Today's Date: <?= date("Y-m-d H:i:s") ?></div>
        <div class="container add_checklist">
            <div class="mb-3">
                <label for="title">Title</label>
                <input type="text" id="title" class="form-control title_add" <?= (!empty($emptyTitle['title']) ? "border border-danger" : (isset($_POST["title"]) && !empty($_POST["title"]) ? "border border-success" : "")) ?> name="title" value="<?= isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : "" ?>">
                <span id="titleError" class="text-danger" style="display: none;"></span>
                <?php if (!empty($title_errors)): ?>
                    <?php foreach ($title_errors as $error): ?>
                        <span class="text-danger">
                            <p><?= htmlspecialchars($error) ?></p>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="content">Text</label>
                <textarea class="form-control title_add" id="content" name="content"><?= $content ?></textarea>
                <span id="contentError" class="text-danger" style="display: none;"></span>
                <?php if (!empty($title_errors)): ?>
                    <?php foreach ($content_errors as $error): ?>
                        <span class="text-danger">
                            <p><?= htmlspecialchars($error) ?></p>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
                <!-- view_error.php -->
                <?php include ("view_error.php"); ?>
            </div>
        </div>
    </form>





    <script>
        var userId = <?= json_encode($note->owner); ?>;
        console.log(userId); // Pour vérifier que la valeur est correctement passée
    </script>
    <script src="JS/edit_errors.js"></script>
</body>

</html>