<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Keep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php include('view/view_menu.php'); ?>
    <div class="view_card container">
        <h6 class="title-notes">Pinned</h6>
        <div class="row">

            <?php foreach ($notes_pinned as $value) { ?>

                <div class="card text-bg-secondary">
                <div class="card-header">
                    <?=$value['title'] ?>
                </div>
                <div class="card-body">
                    <p class="card-text"><?php
                    if ($value['content'] != null) {
                      echo substr($value['content'],0,70) ."...";  
                    }  else {
                        echo "" ; }?></p>
                </div>
                <div class="card-footer">
                <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
                </div>
           <?php } ?> 
        </div>
    </div>
    <div class="view_card container">
        <h6 class="title-notes">Others</h6>
        <div class="row">
            <?php foreach ($notes_unpinned as $value) { ?>

                <div class="card text-bg-secondary">
                <div class="card-header">
                    <?=$value['title'] ?>
                </div>
                <div class="card-body">
                    <p class="card-text"><?php
                    if ($value['content'] != null) {
                      echo substr($value['content'],0,70) ."...";  
                    }  else {
                        echo "" ; }?></p>
                </div>
                <div class="card-footer">
                <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
                </div>
           <?php } ?> 
        </div>
    </div>

</body>
</html>