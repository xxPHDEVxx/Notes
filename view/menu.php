  <div class="menu">

    <input type="checkbox" id="hamburger">
    <label id="hamburger-logo" for="hamburger"> <label id="burger-title">NoteApp</label> </label>

    <nav>
      <a href="note/index">My notes</a>
      <a href="user/my_archives">My archives</a>
      <?php if($names != null ): ?>
        <?php foreach ($names as $name): ?>
         <a href="#">Shared by <?= $name ?></a>
        <?php endforeach; ?>
      <?php endif; ?>
      <a href="settings/settings">Settings</a>
    </nav>
  </div>