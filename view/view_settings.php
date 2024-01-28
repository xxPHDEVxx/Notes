<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class = "title"><h1>Settings</h1></div>
    <div id="settings_div"> <h2>Hey <?= $user->full_name ?> ! </h2></div>
    <div class = "menu_settings">
        <a href="view_editProfile">Edit Profile</a>
        <a href="view_changePassword">Change password</a>
        <a href="view_login">Logout</a>
    </div>

    <?php (new View("menu"))->show(); ?>

</body>
</html>