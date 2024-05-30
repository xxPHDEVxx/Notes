<div class="barre">

    <a class="back" href="<?php echo ($archived == 1) ? 'user/my_archives' : (($is_shared_as_reader == 1 || $is_shared_as_editor == 1) ? "user/get_shared_by/$note->owner" : 'note/index'); ?>"><span class="material-symbols-outlined">arrow_back_ios</span></a>
    <?php if ($archived == 1) : ?>
        <form action="note/delete_note/<?= $note_id ?>" id="deleteForm" method="post">
            <button class="delete" type="submit" id="delete_icon"><span class="material-symbols-outlined">delete_forever</span></button>
        </form>
        <a class="unarchive" href="note/unarchive/<?= $note_id ?>"><span class="material-symbols-outlined">unarchive</span></a>


    <?php elseif ($is_shared_as_editor == 1) : ?>
        <a class="isShared" href="note/edit/<?= $note_id ?>"><span class="material-symbols-outlined">edit</span></a>
    <?php elseif ($archived == 0 && $is_shared_as_editor == 0 && $is_shared_as_reader == 0) : ?>
        <a class="share" href="note/shares/<?= $note_id ?>"><span class="material-symbols-outlined">share</span></a>
        <?php if ($pinned) : ?>
            <a class="pinned" href="note/unpin/<?= $note_id ?>"><span class="material-symbols-rounded">push_pin</span></a>
            <?php else : ?>
                <a class="pinned" href="note/pin/<?= $note_id ?>"><span class="material-symbols-outlined">push_pin</span></a>
            <?php endif; ?>
            <a class="archive" href="note/archive/<?= $note_id ?>"><span class="material-symbols-outlined">archive</span></a>
            <a class="isShared" href="note/edit/<?= $note_id ?>"><span class="material-symbols-outlined">edit</span></a>
        <?php endif; ?>

</div>
<div class="dates">Created <?= $created ?><?= ($edited ? " Edited " . $edited : " Not edited yet") ?></div>
<div class="title_note_title">Title</div>
<div class="title_note"> <?= $note->title ?></div>