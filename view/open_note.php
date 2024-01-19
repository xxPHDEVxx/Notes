<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Note</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    
</head>
<body>
    <div classe="barre">
       
        <a class="lien_liste_note" href="#"><span class="material-symbols-outlined">arrow_back_ios</span></a> 
        <?php if($archived == 1):?>
            <a class="delete" href="#"><span class="material-symbols-outlined" id ="delete_icon">delete_forever</span></a>
            <a class="archived_unarchived" href="#"><span class="material-symbols-outlined">unarchive</span></span></a>
           
        <?php endif; ?>
     
    </div>
    <div class ="dates">Created <?=$created?> Edited <?=$edited?>
    </div>
</body>
</html>