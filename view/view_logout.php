<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>

<body>

<?php include("menu.php"); ?>

    <div class="title">
        <h1 class="page-title">Settings</h1>
    </div>
    <div>
        <h2 class="page-subtitle">Hey <?= $user->full_name ?></h2>
    </div>
    <div class="menu_settings">
        <a href="settings/edit_profile"><i class="fas fa-pen"></i>Edit Profile</a>
        <a href="settings/change_password"><i class="fas fa-key"></i>Change password</a>
        <a href="main/logout"><i class="fas fa-door-open"></i>Logout</a>
    </div>

    <div class="logout">
        <p>Are you sure you want to logout ?</p>
        <form id="logout_form" action="" method="post">
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>
            <button type="submit" name="no" class="btn btn-primary">No</button>
        </form>
    </div>

</body>

</html>