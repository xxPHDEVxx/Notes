

    <div class="menu">
  
        <input type="checkbox" id="hamburger">
        <div class="menu-overlay"></div>
        <label id="hamburger-logo" for="hamburger"><label id="burger-title">NoteApp</label></label>
        <nav>
          <a href="note/index" class="<?=($currentPage == 'my_notes') ? 'active' : 'not_active'?>">My notes</a>
          <a href="user/my_archives" class="<?=($currentPage == 'my_archives') ? 'active' : 'not_active' ?>">My archives</a>
          <?php if($sharers!= null ): ?>
            <?php foreach ($sharers as $userr): ?>
              <a href="user/get_shared_by/<?=$userr->id?>" class="<?=($currentPage == $userr->full_name) ? 'active' : 'not_active' ?> )">Shared by <?= $userr->full_name?></a>
            <?php endforeach; ?>
          <?php endif; ?>
          <a href="settings/settings" class= "<?=($currentPage == 'settings') ? 'active' : 'not_active'?>" >Settings</a>
        </nav>

    </div>
   
   


