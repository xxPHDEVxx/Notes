 
    <div class="barre">
       
        <a class="back" href="#"><span class="material-symbols-outlined">arrow_back_ios</span></a> 
        <?php if($archived == 1):?>
            <a class="delete" href="#"><span class="material-symbols-outlined" id ="delete_icon">delete_forever</span></a>
            <a class="unarchive" href="note/unarchive/<?=$note_id?>"><span class="material-symbols-outlined">unarchive</span></a>
           
        
        <?php elseif($isShared): ?>
            <a class="isShared" href="#"><span class="material-symbols-outlined">edit</span></a>
        <?php else:?>
            <a class="share" href="#"><span class="material-symbols-outlined">share</span>
            <a class="pinned" href="#"><span class="material-symbols-outlined">push_pin</span>
            <a class="archive" href="note/archive/<?=$note_id?>"><span class="material-symbols-outlined">archive</span></a>
            <a class="isShared" href="#"><span class="material-symbols-outlined">edit</span></a>
        <?php endif; ?>    
     
    </div>
    <div class ="dates">Created <?=$created?> <?=($edited ? 'Edited' : 'Not edited yet') ?></div>
    <div class="title_note_title">Title</div>
    <div class="title_note"> <?=$note->title?> </div>
  
