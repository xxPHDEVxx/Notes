


<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Checklist_Note</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    
</head>
<body>
    <?php include("open_note.php"); ?>
    <div class="note_body_title">Items</div>
   
   <div class="note_body_checklist">
 
   <?php foreach($note_body as $row): ?>
    
    <?php if($row['checked']): ?>
      
        <form class="check_form" action="openNote/update_checked" method="post">
       
        <input type="text" name="uncheck"  value="<?=$row["id"]?>" hidden>
        <input class="material-symbols-outlined" id="check_submit" type="submit" value='check_box'>

        
        
        <label class="check_label" for="uncheck"> <?=$row["content"]?></label>
        
    </form>
    
       
    <?php else: ?>
        
        
        <form class="check_form" action="openNote/update_checked" method="post">
        
        <input type="text" name="check"  value="<?=$row["id"]?>" hidden>
        <input class="material-symbols-outlined" id="check_submit" type="submit" value= "check_box_outline_blank" >
        
        
        <label class="uncheck_label" for="check"> <?=$row["content"]?></label>
        </form>
        
     
    <?php endif; ?>
    
    <?php endforeach; ?>





</div>
  
</body>
</html>