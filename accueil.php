     <!DOCTYPE html>
     <html lang="en">
     <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
     </head>
     <body>

  
    <img src="library.png" alt="Un rue en Crète"
       width="150" height="100"
       class="position-fixed top-0 end-0 m-3">
       <div class="container-fluid mt-5">
         <div class="row">
           <div class="col-md-8">
             <p>La bibliothèque de Mouinsort est fermée au public jusqu'à nouvel ordre. Mais il vous est possible de réserver et retirer vos livres via notre service Biblio Drive !</p>
             <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
               <form class="d-flex" action="/action_page.php">
                 <input class="form-control me-2" type="text" placeholder="Rechercher dans le catalogue (saisie du nom de l'auteur)">
                 <button class="btn btn-success" type="submit">Panier</button>
               </form>
             </nav>
           </div>
         </div>
       </div>
     <p>Dernières acquisitions </p>


<?php
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



  
<style>
/* CONTAINER OF SLIDESHOW + LOGIN */
.flex-row {
  display: flex;
  justify-content: center; /* keeps slideshow centered */
  align-items: flex-start;
  gap: 60px; /* space between slideshow and login */
  margin-top: 20px;
}

/* SLIDESHOW BOX */
.slideshow-wrapper {
  text-align: center;
}

/* CSS-only slideshow */
.slideshow-container {
  width: 260px; /* same size as screenshot */
  height: 430px;
  position: relative;
  overflow: hidden;
  margin: auto;
}

.slide {
  width: 100%;
  height: 100%;
  position: absolute;
  opacity: 0;
  animation: fadeSlide 9s infinite;
}

.slide img {
  width: 100%;
  height: auto;
}

.caption {
  font-size: 13px;
  text-align: center;
  margin-top: 5px;
  color: #333;
}

@keyframes fadeSlide {
  0% { opacity: 0; }
  10% { opacity: 1; }
  33% { opacity: 1; }
  43% { opacity: 0; }
  100% { opacity: 0; }
}

.slide:nth-child(1) { animation-delay: 0s; }
.slide:nth-child(2) { animation-delay: 3s; }
.slide:nth-child(3) { animation-delay: 6s; }

/* LOGIN BOX (Right side) */
.login-box {
  border: 1px solid black;
  padding: 15px 20px;
  width: 180px;
  text-align: center;
  background: #fff;
}

.login-box input {
  width: 90%;
  margin: 6px 0;
}

.login-box button {
  margin-top: 10px;
}
</style>

<!-- Title centered above slideshow -->
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



  



     






















</body>
     </html>