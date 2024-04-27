<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared to</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="barre">
        <a class="back" href="<?= $_SESSION['previous_page'] ?>"><span class="material-symbols-outlined">arrow_back_ios</span></a>
    </div>

    <div class="container box-shares">
        <h3>Shares :</h3>
        <?php if (count($sharers) == 0) : ?>
            <p class="share-empty">This note is not shared yet</p>
        <?php endif; ?>

        <div class="my-2">
        <form action="">
        <?php if (count($sharers) > 0) : ?>
        <?php foreach ($sharers as $sharer) : ?>
            <input type="text" name="shares" value="<?= $sharer[1] ?> (<?= ($sharer[2] == 1) ? "editor" : "reader" ?>)" class="form-control-share my-2" disabled>
            <button class="btn btn-primary" type="submit"><=></button>
        <?php endforeach; ?>
        <?php endif; ?>
        </form>
        </div>
        <form class="form-box-share" method="post" action="note/add_share/<?=$note->note_id?>">
        <div class="input-group">
            <select class="form-select form-control-share" name="user" >
                <option selected>-- User --</option>
                <?php foreach ($others as $other) : ?>
                    <?php if ($other->id != $user->id) : ?>
                    <option value="<?= $other->id ?>"><?= $other->full_name ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <select class="form-select form-control-share" name="editor">
                <option selected>-- Permission --</option>
                <option value="0">Reader</option>
                <option value="1">Editor</option>
            </select>
            <button class="btn btn-primary" type="submit">+</button>
        </div>
        </form>
    </div>

</body>

</html>