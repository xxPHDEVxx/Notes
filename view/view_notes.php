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
        <?php if (count($notes_pinned) != 0 && count($notes_unpinned) != 0) :  ?>
            <?php for ($i=0; $i < count($notes_pinned); $i++) { ?>
                <div class="note">

               
                    

                    <p class="note-title"><?= $notes_pinned[$i]["title"]; ?></p>
                    <a class="link-note-archivee" href='openNote/index/<?= $notes_pinned[$i]["id"]; ?>'>

                        <div class="note-content">
                            <?php if ($notes_pinned[$i]["content"]) : ?>
                                <div class="content_text">

                                    <?= $notes_pinned[$i]["content"] ?>

                                </div>

                            <?php else : ?>
                                <div class="content_check">
                                    <div class="check_item">
                                        <?php foreach ($notes_pinned[$i]["content_checklist"] as $checklist_item) : ?>

                                            <?php if (!$checklist_item["checked"]) : ?>
                                                <div class="unchecked_item"><?= $checklist_item["content"] ?></div>
                                            <?php else : ?>
                                                <div class="checked_item"><?= $checklist_item["content"] ?></div>
                                            <?php endif; ?>

                                        <?php endforeach; ?>

                                    </div>
                                </div>

                            <?php endif; ?>


                        </div>

                    </a>
                    <div class="card-footer">
                    <?php if ($i > 0) : ?>

                        <form action="note/move_up" class="left" method="post">
                            <input name="up" type="number" value='<?=$notes_pinned[$i]["id"] ?>' hidden>
                            <input type='submit' value='<<'>
                        </form>
                        <?php endif; ?>
                    <?php if ($i < count($notes_pinned)) : ?>
                        <form action="note/move_down" class="right" method="post">
                            <input name="down" type="number" value='<?=$notes_pinned[$i]["id"] ?>' hidden>
                            <input type='submit' value='>>'>

                        </form>
                        <?php endif; ?>
                    </div>
                </div>                
               <?php }             ?>

        
            </div>


            <p class="title-note">Others</p> 
            <div class="view_notes">
            <?php for ($i=0; $i < count($notes_unpinned); $i++) { ?>
                <div class="note">

                   


                    <p class="note-title"><?= $notes_unpinned[$i]["title"] ;  ?></p>
                    <a class="link-note-archivee" href='openNote/index/<?= $notes_unpinned[$i]["id"]; ?>'>

 
                        <div class="note-content">
                            <?php if ($notes_unpinned[$i]["content"]) : ?>
                                <div class="content_text">

                                    <?= $notes_unpinned[$i]["content"] ?>

                                </div>

                            <?php else : ?>
                                <div class="content_check">
                                    <div class="check_item">
                                        <?php foreach ($notes_unpinned[$i]['content_checklist'] as $checklist_item) : ?>

                                            <?php if (!$checklist_item["checked"]) : ?>
                                                <div class="unchecked_item"><?= $checklist_item["content"] ?></div>
                                            <?php else : ?>
                                                <div class="checked_item"><?= $checklist_item["content"] ?></div>
                                            <?php endif; ?>

                                        <?php endforeach; ?>

                                    </div>
                                </div>

                            <?php endif; ?>


                        </div>

                    </a>
                    <div class="card-footer">
                    <?php if ($i > 0) : ?>

 
                        <form action="note/move_up" class="left" method="post">
                            <input type="number" value='<?=$notes_unpinned[$i]["id"] ?>' hidden>
                            <input type='submit' value='<<'>
                        </form>
                        <?php endif; ?>
                    <?php if ($i < count($notes_unpinned)) : ?>
                        <form action="note/move_down" class="right" method="post">
                            <input type="number" value='<?=$notes_unpinned[$i]["id"] ?>' hidden>
                            <input type='submit' value='>>'>

                        </form>
                        <?php endif; ?>
                    </div>
                </div>                
               <?php }             ?>



               </div>
        <?php else: ?>
            <p class="title-empty">Your notes are empty</p>
        <?php endif; ?>
    </div>

</body>

</html>