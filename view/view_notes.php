<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Keep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <?php include('view/menu.php'); ?>
    <h1>My notes</h1>
    <p class="title-note">Pinned</p> 
    <div class="view_notes">
        <?php if (count($notes) != 0) : ?>

            <?php for ($i=0; $i < count($notes); $i++) { 
                if ($notes[$i]->get_infos()->isPinned()) : ?>
                <div class="note">
                    <p class="note-title"><?= $notes[$i]->get_infos()->get_title(); ?></p>
                    <a class="link-note-archivee" href="#">
                        <div class="note-content">
                            <?php if ($notes[$i]->get_type() == "TextNote") : ?>
                                <div class="content_text">

                                    <?= $notes[$i]->content ?>

                                </div>

                            <?php else : ?>
                                <div class="content_check">
                                    <div class="check_item">
                                        <?php foreach ($notes[$i]->get_items() as $checklist_item) : ?>

                                            <?php if (!$checklist_item->checked) : ?>
                                                <div class="unchecked_item"><?= $checklist_item->content ?></div>
                                            <?php else : ?>
                                                <div class="checked_item"><?= $checklist_item->content ?></div>
                                            <?php endif; ?>

                                        <?php endforeach; ?>

                                    </div>
                                </div>

                            <?php endif; ?>


                        </div>

                    </a>
                    <div class="card-footer">
                    <?php if ($i > 0) : ?>
                    <form>
                            <input type="number" value='<?=$notes[$i]->get_infos()->getNote_id() ?>' hidden>
                            <span><<</span>
                        </form>
                        <?php endif; ?>
                    <?php if ($i < count($notes)) : ?>
                        <form>
                            <input type="number" value='<?=$notes[$i]->get_infos()->getNote_id() ?>' hidden>
                            <span>>><span>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>                
               <?php endif;            ?>
               <?php }             ?>

        
            </div>
            <p class="title-note">Others</p> 
            <div class="view_notes">

            <?php for ($i=0; $i < count($notes); $i++) { 
                if (!$notes[$i]->get_infos()->isPinned()) : ?>
                <div class="note">
                    <p class="note-title"><?= $notes[$i]->get_infos()->get_title() ;  ?></p>
                    <a class="link-note-archivee" href="#">
                        <div class="note-content">
                            <?php if ($notes[$i]->get_type() == "TextNote") : ?>
                                <div class="content_text">

                                    <?= $notes[$i]->content ?>

                                </div>

                            <?php else : ?>
                                <div class="content_check">
                                    <div class="check_item">
                                        <?php foreach ($notes[$i]->get_items() as $checklist_item) : ?>

                                            <?php if (!$checklist_item->checked) : ?>
                                                <div class="unchecked_item"><?= $checklist_item->content ?></div>
                                            <?php else : ?>
                                                <div class="checked_item"><?= $checklist_item->content ?></div>
                                            <?php endif; ?>

                                        <?php endforeach; ?>

                                    </div>
                                </div>

                            <?php endif; ?>


                        </div>

                    </a>
                    <div class="card-footer">
                    <?php if ($i > 0) : ?>
                    <form>
                            <input type="number" value='<?=$notes[$i]->get_infos()->getNote_id() ?>' hidden>
                            <span><<</span>
                        </form>
                        <?php endif; ?>
                    <?php if ($i < count($notes)) : ?>
                        <form>
                            <input type="number" value='<?=$notes[$i]->get_infos()->getNote_id() ?>' hidden>
                            <span>>><span>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>                
               <?php endif;            ?>
               <?php }             ?>



               </div>
        <?php else: ?>
            <p class="title-empty">Your notes are empty</p>
        <?php endif; ?>
    </div>

</body>

</html>