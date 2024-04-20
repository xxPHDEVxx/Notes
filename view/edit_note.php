<div class="edit">
    <a class="back" href="<?= $_SESSION['previous_page'] ?>"><span class="material-symbols-outlined">arrow_back_ios</span></a> 

<a class="save" href="save"><span class="material-symbols-outlined">save</span></a>
</div>
<div class="dates">Created <?= $created ?><?= ($edited ? " Edited " . $edited : " Not edited yet") ?></div>
