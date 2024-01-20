<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shared_by</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    
<h1>Shared_by <?=$shared_by_name?></h1>

<div class="shared_by">
    <?php if (count($shared_notes_as_editor) != 0): ?>
        <div class="shared_title">Notes shared to you by <?=$shared_by_name?> as editor</div>
        <div class="shared_editor">
            <?php foreach ($shared_notes_as_editor as $note_item): ?> 
                <?php include("note_in_list.php") ?>   
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (count($shared_notes_as_reader) != 0): ?>
        <div class="shared_title">Notes shared to you by <?=$shared_by_name?> as reader</div>
        <div class="shared_reader">
            <?php foreach ($shared_notes_as_reader as $note_item): ?>
                <?php include("note_in_list.php") ?>    
             
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php (new View("menu"))->show(["sharers"=>$sharers]); ?>


</body>
</html>