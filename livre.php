<?php
require_once "navbar.php";

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

// Récupérer l'ID du livre depuis l'URL
$nolivre = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($nolivre <= 0) {
    header('Location: recherche.php');
    exit;
}

// Récupérer les informations du livre et de l'auteur
$sql = "SELECT l.nolivre, l.titre, l.isbn13, l.detail, a.nom, a.prenom
    FROM livre l
    JOIN auteur a ON l.noauteur = a.noauteur
    WHERE l.nolivre = ?";
$stmt = $connexion->prepare($sql);
$stmt->execute([$nolivre]);
$livre = $stmt->fetch();

if (!$livre) {
    header('Location: recherche.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($livre['titre']) ?> — Biblio-Drive</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="texte.css">
</head>
<body>
<div class="container mt-5">
    <a href="recherche.php" class="btn btn-link">← Retour à la recherche</a>
    <h1 class="mt-3"><?= htmlspecialchars($livre['titre']) ?></h1>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Auteur</h5>
            <p class="card-text"><?= htmlspecialchars($livre['prenom'] . ' ' . $livre['nom']) ?></p>

            <h5 class="card-title">ISBN</h5>
            <p class="card-text"><?= htmlspecialchars($livre['isbn13']) ?></p>

            <h5 class="card-title">Résumé</h5>
            <div class="card-text">
                <?= nl2br(htmlspecialchars($livre['detail'])) ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
