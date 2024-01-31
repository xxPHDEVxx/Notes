
 
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
        
        
    
    
   


