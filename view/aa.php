<?php foreach ($note_body as $row) : ?>
    <?php if ($row['checked']) : ?>
        <form class="check_form" method="post">
            <input type="text" name="uncheck" value="<?= $row["id"] ?>" class="item" hidden>
            <input class="material-symbols-outlined check_submit" type="submit" value='check_box'>
            <label class="check_label item_label" for="uncheck"> <?= $row["content"] ?></label>
        </form>
    <?php else : ?>
        <form class="check_form" method="post">
            <input type="text" name="check" value="<?= $row["id"] ?>" class="item" hidden>
            <input class="material-symbols-outlined check_submit" type="submit" value="check_box_outline_blank">
            <label class="uncheck_label item_label" for="check"> <?= $row["content"] ?></label>
        </form>
    <?php endif; ?>
<?php endforeach; ?>