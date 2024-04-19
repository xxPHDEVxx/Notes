<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Edit Text Note</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />
    <link href="css/style.css" rel="stylesheet" type="text/css"/></head>
<body>
<<<<<<< HEAD
=======
    
    <?php include("open_note.php"); ?>
    <div class="title_note_title">Title</div>
    <div class="title_edit_note"><?= $note->title ?></div>
    <div class="note_body_title">Text</div>
   
        <div class="note_body_text">
        <?=$note_body; ?> 
>>>>>>> develop

<div class="container mt-5">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <?php foreach ($errors as $error): ?>
                <p><?= $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="edit">

</div>
<div class="dates">Created <?= $created ?><?= ($edited ? " Edited " . $edited : " Not edited yet") ?></div>
<form method="post" action="openNote/save_edit_text_note">
    <input type="hidden" name="note_id" value="<?= $note_id ?>">

    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="<?= $note->title; ?>" required>
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Content</label>
        <textarea class="form-control" id="content" name="content" rows="10" required><?= $note->get_content(); ?></textarea>
    </div>
    <div class="edit">
        <a class="back" href="<?=$_SESSION['previous_page']?>"><span class="material-symbols-outlined">arrow_back_ios</span></a>
        <button style="background-color: transparent; border: none; position: absolute; right: 0;" type="submit"><span class="material-symbols-outlined">save</span></button>
    </div>
</form>

</div>

</body>
</html>