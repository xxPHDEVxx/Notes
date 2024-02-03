<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="title">
        <h1>Settings</h1>
    </div>
    <div class="container mt-5">
        <h2>Edit Profile</h2>

        <form id="edit_form" action="settings/edit_profile" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user->mail ?>" required>
            </div>
            <div class="mb-3">
                <label for="fullName" class="form-label">Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" value="<?= $user->full_name ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <?php (new View("menu"))->show(["sharers" => $sharers]); ?>

</body>

</html>