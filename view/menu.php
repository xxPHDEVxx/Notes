
    <div class="menu">
  
        <input type="checkbox" id="hamburger">
        <div class="menu-overlay"></div>
        <label id="hamburger-logo" for="hamburger"><label id="burger-title">NoteApp</label></label>
        <nav>
          <a href="" class="not_active">My notes</a>
          <a href="user/my_archives" class="<?=($currentPage == 'my_archives') ? 'active' : 'not_active' ?>">My archives</a>
          <?php if($sharers!= null ): ?>
            <?php foreach ($sharers as $user): ?>
              <a href="user/get_shared_by/<?=$user->id?>" class="<?=($currentPage == $user->full_name) ? 'active' : 'not_active' ?> )">Shared by <?= $user->full_name?></a>
            <?php endforeach; ?>
          <?php endif; ?>
          <a href="" class= "not_active" >Settings</a>
        </nav>

    </div>
   
   

   
