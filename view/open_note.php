<div class="barre">

    <a class="back" href="<?= $_SESSION['previous_page'] ?>"><span class="material-symbols-outlined">arrow_back_ios</span></a>
    <?php if ($archived == 1) : ?>
        <a class="delete" href="#"><span class="material-symbols-outlined" id="delete_icon">delete_forever</span></a>
        <a class="unarchive" href="openNote/unarchive/<?= $note_id ?>"><span class="material-symbols-outlined">unarchive</span></a>


    <?php elseif ($isShared_as_editor == 1 || $note->owner == $user_id) : ?>
        <a class="isShared" href="openNote/edit/<?=$note_id?>"><span class="material-symbols-outlined">edit</span></a>
    <?php elseif ($archived == 0 && $isShared_as_editor == 0 && $isShared_as_reader == 0) : ?>
        <a class="share" href="#"><span class="material-symbols-outlined">share</span></a>
        <?php if ($pinned) : ?>
            <a class="pinned" href="openNote/unpin/<?= $note_id ?>"><span class="material-symbols-rounded">
                    push_pin
                </span>
            <?php else : ?>
                <a class="pinned" href="openNote/pin/<?= $note_id ?>"><span class="material-symbols-outlined">push_pin</span></a>
            <?php endif; ?>
            <a class="archive" href="openNote/archive/<?= $note_id ?>"><span class="material-symbols-outlined">archive</span></a>
            <a class="isShared" href="#"><span class="material-symbols-outlined">edit</span></a>
        <?php endif; ?>

</div>
<div class="dates">Created <?= $created ?><?= ($edited ? " Edited " . $edited : " Not edited yet") ?></div>
<div class="title_note_title">Title</div>
<div class="title_note"> <?= $note->title ?></div>