  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <link href="css/style.css" rel="stylesheet" type="text/css" />
  </head>

  <body>
    <div class="menu">
      <input type="checkbox" id="hamburger">
      <label id="hamburger-logo" for="hamburger"><label id="burger-title">NoteApp</label></label>
      <nav>
        <a href="#">My notes</a>
        <a href="user/my_archives">My archives</a>
        <?php if ($sharers != null) : ?>
          <?php foreach ($sharers as $user) : ?>
            <a href="user/get_shared_by/<?= $user->id ?>">Shared by <?= $user->full_name ?></a>
          <?php endforeach; ?>
        <?php endif; ?>
        <a href="Settings/settings">Settings</a>
      </nav>
    </div>
  </body>

  </html>