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

            <?php foreach ($shared_notes_as_editor as $shared): ?>    
                <div class="shared-note">
                    <div class="note-title"><?=$shared["title"]?></div>
                    <a class="shared-note-link" href="#">
                        <div class="note-content">
                            <?php if($shared["content"]): ?>
                                <div class="content_text"><?= $shared["content"]?></div>
                            <?php elseif($shared['content_checklist']):?>
                                    <div class="content_check">
                                        <div class="check_item">
                                            <?php foreach($shared["content_checklist"] as $checklist_item): ?>
                                               <?php if(!$checklist_item['checked']): ?>
                                                    <div class="li_unchecked_item"><?= $checklist_item['content']?></div>   
                                               <?php else:?>
                                                     <div class="li_checked_item"><?=$checklist_item['content']?></div> 
                                               <?php endif; ?>
                                            <?php endforeach; ?> 
                                        </div>
                                    </div>
                            <?php endif; ?>
                        </div>  
                    </a>   
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (count($shared_notes_as_reader) != 0): ?>
        <div class="shared_title">Notes shared to you by <?=$shared_by_name?> as reader</div>
        <div class="shared_reader">

            <?php foreach ($shared_notes_as_reader as $shared): ?>    
                <div class="shared-note">
                    <div class="note-title"><?=$shared["title"]?></div>
                    <a class="shared-note-link" href="#">
                        <div class="note-content">
                            <?php if($shared["content"]): ?>
                                <div class="content_text"><?= $shared["content"]?></div>
                            <?php elseif($shared['content_checklist']):?>
                                <div class="content_check">
                                    <div class="check_item">
                                        <?php foreach($shared["content_checklist"] as $checklist_item): ?>
                                            <?php if(!$checklist_item['checked']): ?>
                                                <div class="li_unchecked_item"><?= $checklist_item['content']?></div>   
                                            <?php else:?>
                                                <div class="li_checked_item"><?=$checklist_item['content']?></div> 
                                            <?php endif; ?>
                                        <?php endforeach; ?> 
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>  
                    </a>   
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

       

     
        
<?php (new View("menu"))->show(["sharers"=>$sharers]); ?>


</body>
</html>