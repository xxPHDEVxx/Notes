<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Search</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,200,0,-25">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>

<body>
    <?php include ('view/menu.php'); ?>
    <div class="view_notes_header">
        <h1 class="page-title">Search my notes</h1>
    </div>
    <div class="box-search">
        <p>Search notes by tag : </p>
        <form action="search/index" method="post">
            <?php foreach ($labels as $label): ?>
                <label for="<?= $label ?>" class="radio-label">
                    <input type="checkbox" id="<?= $label ?>" name="check[]" value="<?= $label ?>" class="check-box-label"
                        <?php if (isset($labels_checked)) {
                            if (in_array($label, $labels_checked)) {
                                echo 'checked';
                            }
                        } ?>>
                    <span class="custom-checkbox-label"></span>
                    <?= $label ?>
                </label>
            <?php endforeach; ?>
            <br><button id="searchButton" class="btn btn-primary" type="submit">Search</button>
        </form>
        <div class="result-note">
            <p class="title_note_pinned">Your notes : </p>
            <div id="pinned" class="view_notes_pinned_unpinned">
                <?php if (count($notes) != 0): ?>
                    <?php for ($i = 0; $i < count($notes); $i++): ?>
                        <div class="note" id="note_<?= $notes[$i]["id"] ?>">
                            <p class="note-title"><?= $notes[$i]["title"] ?></p>
                            <a class="link-note-archivee" href='note/open_note/<?= $notes[$i]["id"] ?>/<?= $notes_coded ?>/<?= $labels_checked_coded ?>'>
                                <div class="note-content">
                                    <?php if ($notes[$i]["content"]): ?>
                                        <div class="content_text">
                                            <?= $notes[$i]["content"] ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="content_check">
                                            <div class="check_item">
                                                <?php foreach ($notes[$i]["content_checklist"] as $checklist_item): ?>
                                                    <?php if (!$checklist_item["checked"]): ?>
                                                        <div class="unchecked_item"><?= $checklist_item["content"] ?></div>
                                                    <?php else: ?>
                                                        <div class="checked_item"><?= $checklist_item["content"] ?></div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>


                                </div>
                                <div class="box-label-note">
                                    <?php $note = Note::get_note_by_id($notes[$i]["id"]);
                                    foreach ($note->get_labels() as $label): ?>
                                        <span class="badge label-note text-bg-secondary"><?= $label ?></span>

                                    <?php endforeach; ?>
                                </div>
                            </a>

                        </div>
                    <?php endfor; ?>
                </div>

            <?php else: ?>
                <p class="title-empty">Nothing checked</p>
            <?php endif; ?>
        </div>
        <footer>
    

        </footer>
    </div>
    </div>

<script src="JS/search.js"></script>
</body>

</html>