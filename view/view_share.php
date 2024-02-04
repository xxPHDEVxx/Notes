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
    <h3>Shares :</h3>
    <div class="container">
        <p class="share-empty">This note is not shared yet</p>
        <form>
            <div class="input-group">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>-- User --</option>
                        <?php 
                        foreach( $all_users as $other):
                            if ($other->id != $user->id) :?>
                                
                                <option value="1"><?= $other->full_name?></option>
                            <?php endif;?>
                        <?php endforeach;?>
                    </select>
                    <select class="form-select" aria-label="Default select example">
                        <option selected>-- Permission --</option>
                        <option value="1">Reader</option>
                        <option value="2">Editor</option>
                    </select>
                    <button class="btn btn-primary">+</button>
            </div>  
        </form>
    </div>
        <?php // if () :  ?>

        <?php //else: ?>
        <?php //endif; ?>

</body>

</html>