<!DOCTYPE html>
<?php
  session_start();
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche d'auteur - Biblio-Drive</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <img src="library.png" alt="Bibliothèque" width="150" height="100" class="position-fixed top-0 end-0 m-3" class = "page-with-login">
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-lg-7 col-md-8">


<?php
// Connexion à la base de données
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

// Récupérer la saisie de l'auteur
$auteurRecherche = isset($_GET['author']) ? trim($_GET['author']) : '';
$resultats = [];
$messageResultat = '';

// Si l'utilisateur a tapé quelque chose, on lance la recherche
if ($auteurRecherche !== '') {
  // On découpe la recherche en "mots" (ex: "Victor Hugo" -> ["Victor","Hugo"])
    $tokens = preg_split('/\s+/', $auteurRecherche, -1, PREG_SPLIT_NO_EMPTY);

    // On prépare une requête dynamique: chaque mot doit apparaître dans nom OU prénom
    $whereParts = [];
    $params = [];
    foreach ($tokens as $t) {
        $whereParts[] = "(a.nom LIKE ? OR a.prenom LIKE ?)";
        $params[] = "%{$t}%";
        $params[] = "%{$t}%";
    }

    
    // Si aucun mot, on force une condition fausse
    if (empty($whereParts)) {
        $whereSql = "1=0";
    } else {
        
        $whereSql = implode(' AND ', $whereParts);
    }

    // Requête: récupérer les livres + auteur correspondant à la recherche
    $sql = "SELECT l.nolivre, l.titre, a.nom, a.prenom
            FROM livre l
            JOIN auteur a ON l.noauteur = a.noauteur
            WHERE " . $whereSql . "
            ORDER BY l.titre ASC";

    $stmt = $connexion->prepare($sql);
    $stmt->execute($params);
    $resultats = $stmt->fetchAll();

    // Message affiché à l'utilisateur (nombre de résultats)
    if (empty($resultats)) {
        $messageResultat = "Aucun livre trouvé pour l'auteur « <strong>" . htmlspecialchars($auteurRecherche) . "</strong> ».";
    } else {
        $messageResultat = count($resultats) . " livre(s) trouvé(s) pour l'auteur « <strong>" . htmlspecialchars($auteurRecherche) . "</strong> ».";
    }
}
?>

    
<!-- Barre de navigation (avec recherche + liens) -->
    <?php require "navbar.php"; ?>

<div class="container-fluid mt-3">
  <div class="row">
    <div class="col-lg-7 col-md-8">

      
      <?php if ($auteurRecherche !== ''): ?>
        <div class="alert alert-info mb-4">
          <?php echo $messageResultat; ?>
        </div>

        <?php if (!empty($resultats)): ?>
          <div class="list-group">
            <?php foreach ($resultats as $livre): ?>
              <a href="livre.php?id=<?= (int)$livre['nolivre'] ?>" 
                 class="list-group-item list-group-item-action">
                <h5 class="mb-1"><?= htmlspecialchars($livre['titre']) ?></h5>
                <p class="mb-0 text-muted">
                  par <?= htmlspecialchars($livre['prenom'].' '.$livre['nom']) ?>
                </p>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

      <?php else: ?>
        <div class="alert alert-secondary">
          Entrez le nom d'un auteur pour voir la liste de ses livres disponibles.
        </div>
      <?php endif; ?>
      
    </div>
  </div>
</div>

</div>
