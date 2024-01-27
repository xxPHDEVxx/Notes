
    <div class="menu">
      <input type="checkbox" id="hamburger">
      <label id="hamburger-logo" for="hamburger"><label id="burger-title">NoteApp</label></label>
      <nav>
        <a href="#">My notes</a>
        <a href="user/my_archives">My archives</a>
        <?php if($sharers!= null ): ?>
          <?php foreach ($sharers as $user): ?>
            <a href="user/get_shared_by/<?=$user->id?>">Shared by <?= $user->full_name?></a>
          <?php endforeach; ?>
        <?php endif; ?>
        <a href="#">Settings</a>
      </nav>
    </div>
