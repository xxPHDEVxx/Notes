<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a checklist</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.cs" />
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="css/style.css" rel="stylesheet" type="text/css">

</head>

<body>
    <form method="post" action="note/add_checklist_note">
        <div class="add_header">
            <a class="back" href="<?= $_SESSION['previous_page'] ?>"><span class="material-symbols-outlined">arrow_back_ios</span></a>

            <button class="save" type="submit"><span class="material-symbols-outlined">save</span></button>
        </div>
        <div class="container add_checklist">
            <div class="mb-3">
                <label for="validationCustom01">Title</label>
                <input type="text" class="form-control title_add <?= (!empty($errors['title']) ? "border border-danger" : (isset($_POST["title"]) && !empty($_POST["title"]) ? "border border-success" : "")) ?>" name="title" value="<?= isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : "" ?>"> <?php if (!empty($errors['title'])) : ?>
                    <span class="text-danger"><?= $errors['title'] ?></span>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="items">Items</label>
                <ul>
                    <?php for ($i = 0; $i < 5; $i++) : ?>
                        <li>
                            <input type="text" class="form-control item_add <?= (!empty($errors["item_$i"]) ? "border border-danger" : (isset($_POST['items'][$i]) && !empty($_POST['items'][$i]) ? "border border-success" : "")) ?>" name="items[]" value="<?= isset($_POST['items'][$i]) ? htmlspecialchars($_POST['items'][$i]) : "" ?>">
                            <?php if (!empty($errors["item_$i"])) : ?>
                                <span class="text-danger"><?= $errors["item_$i"] ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
    </form>
</body>