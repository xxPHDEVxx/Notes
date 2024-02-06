<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Text Note</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
</head>

<body>


    <div class="container">
        <!-- Icône d'une flèche vers la gauche pour revenir en arrière -->
        <div class="back-icon">
            <i class="fas fa-angle-left fa-lg"></i>
        </div>
        <!-- Icône de cassette pour enregistrer -->
        <div class="save-icon">
            <h1><a href="note/edit_text_note"><i class="fas fa-save fa-lg"></i></a></h1>
        </div>

        <!-- Formulaire d'édition de la note -->
        <form class="edit_form">
            <div class="mb-3">
                <label for="note-title" class="form-label">Title</label>
                <input type="text" class="form-control" id="note-title" name="note-title" placeholder="Enter title">
            </div>
            <div class="mb-3">
                <label for="note-content" class="form-label">Content</label>
                <textarea class="form-control" id="note-content" name="note-content" rows="10" placeholder="Enter note content"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

</body>

</html>