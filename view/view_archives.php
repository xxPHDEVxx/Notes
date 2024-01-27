<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My archives</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
   
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
  


    <h1>My archives</h1>
    <h2>Archives</h2> 
    <div class="my-archives">
        <?php if (count($archives) != 0): ?>
         
            <?php foreach ($archives as $note_item): ?>    
                <?php include("note_in_list.php") ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php include("view_menu.php"); ?>
     
    </div>
 
   
   
 


</body>
</html>