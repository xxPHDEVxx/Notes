<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
   
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <div class="container">
    <div class="main" id ="login">
    <div class="title">Sign in</div>
    <form action="main/login" method="post">
            <div class="inputbox text">
            <i class="material-symbols-outlined">person</i>
            <input id="mail" name="mail" type="text" placeholder="Email" value="<?= $mail ?>">
            </div>
             <div class="inputbox text">
             <i class="material-symbols-outlined">key</i>
             <input id="password" name="password" type="text" placeholder="password" value="<?= $password ?>">
            </div>
            <?php if (count($errors) != 0): ?>
                <div class="errors"> 
                    
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>    
                    </ul>  
                </div> 
            <?php endif; ?> 

                    
            <div class="inputbox button login">
                <input type="submit" value="Login">
                <div class="subscribe-link"><a href="">New here ? Click to subscribe !</a></div>
            </div>    
    </form> 
              
            
     </div>
    </div>
  </body>
</html>