<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet Keep</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="fontawesome-free-6.5.2-web/css/regular.css">

   
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script>
        $(document).ready(function() {
             $("#pinned").sortable({ 
                connectWith: "#unpinned",
                update: function(event, ui) {
                    var order = $(this).sortable("serialize") + '&update=update';
                    $.ajax({
                        url: "note/drag_and_drop",
                        type: "POST",
                        data: order,
                        success: function(response) {
                            console.log(response); 
                           
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                });
                }
            });
             $("#unpinned").sortable({
                 connectWith: "#pinned",
                 update: function(event, ui) {
                    var order = $(this).sortable("serialize") + '&update=update';
                    $.ajax({
                        url: "note/drag_and_drop",
                        type: "POST",
                        data: order,
                        success: function(response) {
                            console.log(response); 
                        }
                    });
                } 
                });
        
        });
    </script>
   
</head>


<body>
    <?php include('view/menu.php'); ?>
    <div class="view_notes_header">
    <h1>My notes</h1>
    </div>
    <p class="title_note_pinned">Pinned</p> 
    <div id="pinned" class="view_notes_pinned_unpinned">
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
                            <input class="material-symbols-outlined"type='submit' value="keyboard_double_arrow_left">
                        </form>
                        <?php endif; ?>
                    <?php if ($i < count($notes_pinned) - 1) : ?>
                        <form action="note/move_down" class="right" method="post">
                            <input name="down" type="number" value='<?=$notes_pinned[$i]["id"] ?>' hidden>
                          <input class="material-symbols-outlined"type='submit' value="keyboard_double_arrow_right">

                            

                        </form>
                        <?php endif; ?>
                    </div>
                </div>                
               <?php }             ?>

        
            </div>


            <p class="title_note_unpinned">Others</p> 
            <div id="unpinned" class="view_notes_pinned_unpinned">
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
                            <input name="up" type="number" value='<?=$notes_unpinned[$i]["id"] ?>' hidden>
                            <input class="material-symbols-outlined"type='submit' value="keyboard_double_arrow_left">
                        </form>
                        <?php endif; ?>
                    <?php if ($i < count($notes_unpinned) - 1) : ?>
                        <form action="note/move_down" class="right" method="post">
                            <input name="down" type="number" value='<?=$notes_unpinned[$i]["id"] ?>' hidden>
                            <input class="material-symbols-outlined"type='submit' value="keyboard_double_arrow_right">

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
    <footer class="">
            <div class="position-absolute bottom-0 w-100 float-end">
            <a href="note/add_checklist_note">
                
                <span class="material-symbols-outlined text-warning text-lg  text-lg-end m-2 float-end">checklist</span>
            </a>
                <a href="openNote/add_text_note">
                 <!--   <i class="fa-solid fa-note-sticky text-lg  text-lg-end m-2 float-end text-warning"></i>-->
                    <span class="material-symbols-outlined text-warning text-lg  text-lg-end m-2 float-end">draft</span>
                </a>
           
          
            </div>
          
             

               
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  
</body>

</html>