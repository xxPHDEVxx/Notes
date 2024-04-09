<div class="edit">
    <a class="back" href="<?= $_SESSION['previous_page'] ?>"><span class="material-symbols-outlined">arrow_back_ios</span></a> 
</div>
<a class="Save" href="#"><span class="material-symbols-outlined">
save
</span>
<div class="dates">Created <?= $created ?><?= ($edited ? " Edited " . $edited : " Not edited yet") ?></div>
<div class="title_note_title">Title</div>
<div class="title_note"> <?= $note->title ?></div>