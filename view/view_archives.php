<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My archives</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    </div>
    <?php (new View("menu"))->show(["sharers"=>$sharers]); ?>


</body>
</html>