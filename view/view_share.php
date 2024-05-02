<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared to</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,200,0,-25" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body>
    <div class="barre">
        <a class="back" href="<?= $_SESSION['previous_page'] ?>"><span class="material-symbols-outlined">arrow_back_ios</span></a>
    </div>

    <div class="container box-shares">
        <h3>Shares :</h3>
        <?php if (count($sharers) == 0) : ?>
            <p class="share-empty">This note is not shared yet</p>
        <?php else : ?>
            <?php foreach ($sharers as $sharer) : ?>
                <form class="form_toggle" action="note/toggle_permission/<?= $note->note_id ?> " method="post">
                    <div class="box-sharer">
                        <input type="text" name="share" value="<?= $sharer[0] ?>" hidden>
                        <input type="text" name="edit" value="<?= $sharer[2] ?>" hidden>
                        <p class="form-control-share form-share my-2"><?= $sharer[1] ?> (<span class="role"><?= ($sharer[2] == 1) ? "editor" : "reader" ?></span>)</p>
                        <button class="btn btn-primary btn-share btn-toggle" type="submit" name="action" value="toggle"><span class="material-symbols-outlined ">change_circle</span></button>
                        <button class="btn btn-danger btn-share btn-del" type="submit" name="action" value="delete"><span class="material-symbols-outlined">minimize </span></button>
                </form>
            <?php endforeach; ?>
        <?php endif; ?>
        <form class="form-box-share" method="post" action="note/shares/<?= $note->note_id ?>">
            <div class="input-group">
                <select class="form-select form-control-share" name="user">
                    <option selected value="null">-- User --</option>
                    <?php foreach ($others as $other) : ?>
                        <?php if ($other->id != $user->id) : ?>
                            <option value="<?= $other->id ?>"><?= $other->full_name ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <select class="form-select form-control-share" name="editor">
                    <option selected value="null">-- Permission --</option>
                    <option value="0">Reader</option>
                    <option value="1">Editor</option>
                </select>
                <button class="btn btn-primary" type="submit">+</button>
            </div>
        </form>
        <?php endif; ?>

        <div>
            <?php if (count($sharers) > 0) : ?>
                <?php foreach ($sharers as $sharer) : ?>
                    <form action="note/toggle_permission/<?=$note->note_id?> " method="post">
                        <div class="box-sharer">
                            <input type="text" name="share" value="<?= $sharer[0] ?>" hidden>
                            <input type="text" name="edit" value="<?= $sharer[2] ?>" hidden>
                            <input type="text" value="<?= $sharer[1] ?> (<?= ($sharer[2] == 1) ? "editor" : "reader" ?>)" class="form-control-share form-share my-2" disabled>
                            <button class="btn btn-primary btn-share btn-toggle" type="submit" name="action" value="toggle"><span class="material-symbols-outlined ">
                                    change_circle
                                </span></button>
                            <button class="btn btn-danger btn-share btn-del" type="submit" name="action" value="delete"><span class="material-symbols-outlined "><span class="material-symbols-outlined">
                                        minimize
                            </span></button>
                    </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php if (count($others)> 0) :?>
<form class="form-box-share" method="post" action="note/shares/<?= $note->note_id ?>">
    <div class="input-group">
        <select class="form-select form-control-share" name="user">
            <option selected value="null">-- User --</option>
            <?php foreach ($others as $other) : ?>
                    <option value="<?= $other->id ?>"><?= $other->full_name ?></option>
            <?php endforeach; ?>
        </select>
        <select class="form-select form-control-share" name="editor">
            <option selected value="null">-- Permission --</option>
            <option value="0">Reader</option>
            <option value="1">Editor</option>
        </select>
        <button class="btn btn-primary" type="submit">+</button>
    </div>
</form>
<?php endif;?>

    </div>
    <script src="JS/toggle_permission.js"></script>
</body>

</html>