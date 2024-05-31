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
        <p>Search notes by tag : </p>
        <form action="search/index" method="post">
            <?php foreach ($labels as $label) : ?>
                <label for="<?= $label ?>" class="radio-label">
                    <input type="checkbox" id="<?= $label ?>" name="check[]" value="<?= $label ?>" class="check-box-label" <?php if(isset($_POST['check'])) { if(in_array($label, $_POST['check'])) { echo 'checked'; } } ?>>
                    <span class="custom-checkbox-label"></span>
                    <?= $label ?>
                </label>
            <?php endforeach; ?>
            <br><button class="btn btn-primary" type="submit">Search</button>
        </form>
        <div class="result-note">
            <p>Your notes : </p>
            <div class="view-notes-search">
                <div class="note" id="note">
                    <p class="note-title">titre</p>
                    <a class="link-note-archivee" href='note/open_note/ ?>'>
                        <div class="note-content">
                            text
                            <!-- <?php if ($notes_pinned[$i]["content"]) : ?>
                                <div class="content_text">
                                    <?= $notes_pinned[$i]["content"] ?>
                                </div>
                            <?php else : ?> -->
                                <!-- <div class="content_check">
                                    <div class="check_item">
                                        <?php foreach ($notes_pinned[$i]["content_checklist"] as $checklist_item) : ?>
                                            <?php if (!$checklist_item["checked"]) : ?>
                                                <div class="unchecked_item"><?= $checklist_item["content"] ?></div>
                                            <?php else : ?>
                                                <div class="checked_item"><?= $checklist_item["content"] ?></div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?> -->

                            <div class="box-label-note">
                                <span class="badge label-note text-bg-secondary">label</span>
                                <!-- <?php $note = Note::get_note_by_id($notes_pinned[$i]["id"]);
                                foreach ($note->get_labels() as $label) : ?>
                                    <span class="badge label-note text-bg-secondary"><?= $label ?></span>

                                <?php endforeach; ?> -->
                            </div>

                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>