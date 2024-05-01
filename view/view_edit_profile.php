<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" >
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" >
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="title">
        <div>
            <h1>Settings</h1>
        </div>
        <div class="back-icon">
            <a href="settings/settings"><span class="material-symbols-outlined">arrow_back_ios</span></a>
        </div>
    </div>
    <div class="container mt-5">
        <h2>Edit Profile</h2>

        <form class="edit_form" action="settings/edit_profile" method="post">
    <div class="mb-3">
        <label for="email" class="form-label">Mail</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($user->mail) ?>" required>
    </div>
    <div class="mb-3">
        <label for="fullName" class="form-label">Name</label>
        <input type="text" class="form-control" id="fullName" name="fullName" value="<?= isset($_POST['fullName']) ? htmlspecialchars($_POST['fullName']) : htmlspecialchars($user->full_name) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>

    </div>

    <?php if (!empty($errors)) : ?>
        <div id="alertPassword" class="alert alert-danger" role="alert">
            <?php foreach ($errors as $error) : ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


    <?php if (isset($successMessage)) : ?>
        <div id="alertPassword" class="alert alert-success" role="alert">
            <p><?= $successMessage ?></p>
        </div>
    <?php endif; ?>



</body>

</html>