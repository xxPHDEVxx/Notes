<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
   <div class="note-archivee">
        <a class="link-note-archivee" href="Note/open_note/<?=$note_item["id"]?>">
            <div class="note-title"><?=$note_item["title"]?></div>
                <div class="note-content">
                    <?php if($note_item["content"]): ?>
                        <div class="content_text"><?= $note_item["content"]?></div>
                    <?php elseif($note_item['content_checklist']):?>
                        <div class="content_check">
                            <div class="check_item">
                                <?php foreach($note_item["content_checklist"] as $checklist_item): ?>
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
        
    
    
   


</body>
</html>