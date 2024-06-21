<div class="barre">

    <?php
    $backHref = '';
    if ($labels_checked_coded != "") {
        $backHref = "search/search/$notes_coded/$labels_checked_coded";
    } else {
        if ($archived == 1) {
            $backHref = 'user/my_archives';
        } elseif ($is_shared_as_reader == 1 || $is_shared_as_editor == 1) {
            $backHref = "user/get_shared_by/$note->owner";
        } else {
            $backHref = 'note/index';
        }
    }
    ?>

    <?php
    if ($labels_checked_coded != "") {
        $param = "$note->note_id/$notes_coded/$labels_checked_coded";
    } else {
        $param = "$note->note_id";
    }
    ?>

    <a class="back" href="<?= $backHref; ?>"><span class="material-symbols-outlined">arrow_back_ios</span></a>
    <?php if ($archived == 1): ?>
        <form action="note/delete_note/<?= $note_id ?>" id="deleteForm" method="post">
            <input type="text" value="<?= $note_id ?>" name="note_id" class="note_id" hidden>

            <button class="delete" type="submit" id="delete_icon"><span
                    class="material-symbols-outlined">delete_forever</span></button>
        </form>
        <a class="unarchive" href="note/unarchive/<?= $note_id ?>"><span
                class="material-symbols-outlined">unarchive</span></a>



    <?php elseif ($is_shared_as_editor == 1): ?>
        <a class="label" href="note/labels/<?= $note_id ?>"><span class="material-symbols-outlined">label</span></a>
        <a class="isShared" href="note/edit/<?= $note_id ?>/<?= $notes_coded ?>/<?= $labels_checked_coded ?>"><span
                class="material-symbols-outlined">edit</span></a>
    <?php elseif ($archived == 0 && $is_shared_as_editor == 0 && $is_shared_as_reader == 0): ?>
        <a class="share" href="note/shares/<?= $param ?>"><span
                class="material-symbols-outlined">share</span></a>
        <?php if ($pinned): ?>
            <a class="pinned" href="note/unpin/<?= $note_id ?>"><span class="material-symbols-rounded">push_pin</span></a>
        <?php else: ?>
            <a class="pinned" href="note/pin/<?= $note_id ?>"><span class="material-symbols-outlined">push_pin</span></a>
        <?php endif; ?>
        <a class="archive" href="note/archive/<?= $note_id ?>"><span class="material-symbols-outlined">archive</span></a>
        <a class="label" href="note/labels/<?= $param ?>"><span
                class="material-symbols-outlined">label</span></a>
        <a class="isShared" href="note/edit/<?= $param ?>"><span
                class="material-symbols-outlined">edit</span></a>
    <?php endif; ?>

</div>
<div class="dates">Created <?= $created ?><?= ($edited ? " Edited " . $edited : " Not edited yet") ?></div>
<div class="title_note_title">Title</div>
<div class="title_note"> <?= $note->title ?></div>