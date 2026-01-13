 <?php
 session_start();
  ?>
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
            <div class="col-12">
                
              <?php
              require_once "navbar.php";
              
              // Connexion à la base de données avec PDO
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
              
              // Récupération des 3 derniers livres avec photo
              $sql = "SELECT titre, photo, anneeparution 
                      FROM livre 
                      WHERE photo IS NOT NULL AND photo <> ''
                      ORDER BY dateajout DESC 
                      LIMIT 3";
              $stmt = $connexion->query($sql);
              $livres = $stmt->fetchAll();
              
              
              // Si aucun livre avec photo n'est trouvé, on prend des images du dossier /covers comme solution de secours
              if (count($livres) === 0) {
                $files = array_values(array_filter(scandir(__DIR__ . '/covers'), function($f) {
                  return preg_match('/\.(jpe?g|png|gif)$/i', $f);
                }));
                $files = array_slice($files, 0, 3);
                foreach ($files as $f) {
                  $livres[] = ['titre' => pathinfo($f, PATHINFO_FILENAME), 'photo' => $f, 'anneeparution' => null];
                }
              }
              ?>
              
              
              
              <h2 class="text-center mb-4">Dernières acquisitions</h2>
              <?php include "slideshow.php"; ?>
              
              </body>
                   </html>

                
          


              




