<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit checklist note"<?= $note->title ?>"</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <form method="post" action="note/edit_checklist/<?= $note_id ?>">
        <div class="edit">
            <?php
            if ($labels_checked_coded != "") {
                $href = "note/open_note/$note_id/$notes_coded/$labels_checked_coded";
            } else {
                $href = "note/open_note/$note_id";
            }
            ?>
            <a class="back" href="<?= $href ?>"><span
                    class="material-symbols-outlined">arrow_back_ios</span></a>
            <button class="save" type="submit" id="saveButton" name="save"><span
                    class="material-symbols-outlined">save</span></button>
        </div>
        <div class="dates">Created <?= $created ?><?= ($edited ? " Edited " . $edited : " Not edited yet") ?></div>
        <div class="container_edit">
            <label for="title">Title</label>
            <input type="text" class="title_edit_note" id="title" name="title" value="<?= $note->title ?>">
            <span id="titleError" class="text-danger" style="display: none;"></span>
            <?php if (!empty($errors['title'])): ?>
                <p class="text-danger erreur_edit"><?= $errors['title'] ?></p>
            <?php endif; ?>
            <span class="note_body_edit">Items</span>
            <div class="note_body_checklist_edit">
                <div id="container-item">

                    <?php foreach ($content as $row):
                        $id = $row['id'] ?>
                        <div class="item">
                            <div class="edit_checklist_form" id="div<?= $id ?>">
                                <div class="edit_check_div">
                                    <input class="check_square" type="checkbox" value="<?= $id ?>" name="box"
                                        <?= $row["checked"] ? 'checked' : '' ?> disabled>
                                </div>
                                <input type="text" name="items[<?= $id ?>]"
                                    class="checklist_elements <?= $row["checked"] ? 'check_label' : '' ?>" id="item_content"
                                    value="<?= isset($_POST["items[<?= $id ?>]"]) ? htmlspecialchars($_POST["items[<?= $id ?>]"]) : $row["content"] ?>">

                                <input type="hidden" name="remove" value="<?= $id ?>">
                                <button type="submit" id="delete<?= $id ?> " name="delete" value="<?= $id ?>"
                                    class="icone-delete">-</button>
                            </div>
                            <span id="contentError_<?= $id ?>" class="text-danger" style="display: none;"></span>
                            <?php if (!empty($errors["item_$id"])): ?>
                                <p class="text-danger"><?= $errors["item_$id"] ?></p>
                                <?php
                            endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <label for="new">New item</label>
                <div class="edit_checklist_form">
                    <input type="text" class="form-control-edit" id="new" name="new"
                        value="<?= isset($_POST["new"]) ? htmlspecialchars($_POST["new"]) : "" ?>">
                    <button type="submit" id="addButton" class="icone-add">+</button>
                    <span id="newContentError" class="text-danger" style="display: none;"></span>
                    <?php if (!empty($errors['items'])): ?>
                        <p class="text-danger erreur_edit"><?= $errors['items'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
    </form>
    </div>

    <?php include ("view_modal.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>var note = <?= json_encode($note_id) ?>;</script>
    <script src="JS/edit_checklist_errors.js"></script>
    <script src="JS/confirmation_edit_check.js"></script>
</body>

</html>