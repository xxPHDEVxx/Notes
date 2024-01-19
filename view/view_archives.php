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
            <?php foreach ($archives as $archive): ?>    
                <div class="note-archivee">
                <a class="link-note-archivee" href="Note/open_note/<?=$archive["id"]?>">
                    <div class="note-title"><?=$archive["title"]?></div>
                              
                        <div class="note-content">
                            <?php if($archive["content"]): ?>
                                <div class="content_text"><?= $archive["content"]?></div>
                            <?php elseif($archive['content_checklist']):?>
                                <div class="content_check">
                                    <div class="check_item">
                                        <?php foreach($archive["content_checklist"] as $checklist_item): ?>
                                            <?php if(!$checklist_item['checked']): ?>
                                                <div class="unchecked_item"><?= $checklist_item['content']?></div>   
                                            <?php else:?>
                                                <div class="checked_item"><?=$checklist_item['content']?></div> 
                                            <?php endif; ?>
                                        <?php endforeach; ?> 
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>  
                    </a>   
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php (new View("menu"))->show(["sharers"=>$sharers]); ?>


</body>
</html>