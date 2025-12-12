
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark py-2 mb-4">
                    <form class="form-inline d-flex gap-2 w-100 mx-auto" action="recherche.php" method="get">
                        <input name="author" class="form-control flex-grow-1" type="text" 
                               placeholder="Rechercher dans le catalogue (saisir le nom de l'auteur)" 
                               style="background-color: #e7f3ff; height: 32px;" 
                               value="<?php echo isset($_GET['author']) ? htmlspecialchars(trim($_GET['author'])) : ''; ?>">
                        <button class="btn btn-success" type="submit">Recherche</button>
                        <a href="accueil.php" class="btn btn-secondary">Accueil</a>
                    </form>
                </nav>
</body>
</html>
