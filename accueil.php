     <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche d'auteur - Biblio-Drive</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <img src="library.png" alt="Bibliothèque" width="150" height="100" class="position-fixed top-0 end-0 m-3">
    
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <p class="text-muted">La bibliothèque de Moulinsart est fermée au public jusqu'à nouvel ordre. Mais il vous est possible de réserver et retirer vos livres via notre service Biblio Drive !</p>
                
          
              


<?php
require_once "navbar.php";
try {
  $dns = 'mysql:host=localhost;dbname=bibliodrive;charset=utf8mb4';
  $utilisateur = 'root';
  $motDePasse = '';
  $connexion = new PDO($dns, $utilisateur, $motDePasse, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ]);
} catch (Exception $e) {
  die("Connexion à MySQL impossible : " . $e->getMessage());
}

// Load books BEFORE HTML
$sql = "SELECT titre, photo, anneeparution 
        FROM livre 
        WHERE photo IS NOT NULL AND photo <> ''
        ORDER BY dateajout DESC 
        LIMIT 3";
$stmt = $connexion->query($sql);
$livres = $stmt->fetchAll();
?>



  <h3 style="text-align:center;">Dernières acquisitions</h3>

<!-- FLEX ROW: slideshow (center) + login (right) -->
<div class="flex-row">

  <!-- LEFT: Slideshow section -->
  <div class="slideshow-wrapper">
    <div class="slideshow-container">

      <?php foreach ($livres as $livre): ?>
        <div class="slide">
          <img src="covers/<?= htmlspecialchars($livre['photo']) ?>">
        </div>
      <?php endforeach; ?>

    </div>

    <div class="caption">(Carrousel)</div>
  </div>

  <!-- RIGHT: Login form -->
  <div class="login-box">
      <p><strong>Se connecter</strong></p>

      <form action="action_page.php" method="post">
        <label>Identifiant</label><br>
        <input type="text" name="uname" required><br>

        <label>Mot de passe</label><br>
        <input type="password" name="psw" required><br>

        <button type="submit">Connexion</button>
      </form>
  </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

















</body>
     </html>