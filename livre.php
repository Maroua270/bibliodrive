<?php
session_start();
require "connexion.php"; 

$isLoggedIn = isset($_SESSION['mel']);


$nolivre = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($nolivre <= 0) {
    header('Location: recherche.php');
    exit;
}


// Récupérer les infos du livre + son auteur
$sql = "SELECT l.nolivre, l.titre, l.isbn13, l.detail, l.photo, a.nom, a.prenom
        FROM livre l
        JOIN auteur a ON l.noauteur = a.noauteur
        WHERE l.nolivre = ?";
$stmt = $connexion->prepare($sql);
$stmt->execute([$nolivre]);
$livre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livre) {
    header('Location: recherche.php');
    exit;
}


// Vérifier si le livre est actuellement emprunté (dateretour NULL = pas rendu)
$sql = "SELECT 1 FROM emprunter WHERE nolivre = ? AND dateretour IS NULL LIMIT 1";
$stmt = $connexion->prepare($sql);
$stmt->execute([$nolivre]);
$isDisponible = !$stmt->fetchColumn();
$disponibilite = $isDisponible ? "Disponible" : "Indisponible";


// Vérifier si le livre est déjà dans le panier (stocké en session)
$inCart = false;
if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
    $inCart = isset($_SESSION['panier'][(string)$nolivre]);
}

// Si le livre est dans le panier, on le considère indisponible (pour empêcher de re-cliquer)
if ($inCart) {
    $isDisponible = false;
    $disponibilite = "Indisponible";
}


// Message d'avertissement si pas connecté
$warning = "";
if (!$isLoggedIn) {
    $warning = "Pour pouvoir réserver vous devez posséder un compte et vous identifier.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($livre['titre']) ?> — Biblio-Drive</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="page-with-login">

<img src="library.png" alt="Bibliothèque" width="150" height="100" class="position-fixed top-0 end-0 m-3">

<?php require_once "navbar.php"; ?>
<?php

require "flash.php";
$success = flash_get('flash_success');
$warning = flash_get('flash_warning');

if ($success): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if ($warning): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($warning) ?></div>
<?php endif; ?>



<div class="container-fluid mt-5">
    <a href="recherche.php" class="btn btn-link">← Retour à la recherche</a>
    <h1 class="mt-3"><?= htmlspecialchars($livre['titre']) ?></h1>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Auteur</h5>
                    <p class="card-text"><?= htmlspecialchars($livre['prenom'] . ' ' . $livre['nom']) ?></p>

                    <h5 class="card-title">ISBN</h5>
                    <p class="card-text"><?= htmlspecialchars($livre['isbn13']) ?></p>

                    <h5 class="card-title">Résumé</h5>
                    <div class="card-text">
                        <?= nl2br(htmlspecialchars($livre['detail'])) ?>
                    </div>

                   
<div class="d-flex align-items-center gap-3 mt-3">

    <span class="fw-semibold <?= $isDisponible ? 'text-success' : 'text-danger' ?>">
        <?= htmlspecialchars($disponibilite) ?>
    </span>

    <?php if (!$isLoggedIn): ?>

        <?php if ($isDisponible): ?>
            <span class="border border-danger text-danger px-3 py-2 rounded-2">
                Pour pouvoir réserver vous devez posséder un compte et vous identifier.
            </span>
        <?php endif; ?>

    <?php else: ?>

        <?php if ($isDisponible): ?>
            <form action="ajout_panier.php" method="get" class="m-0">
                <input type="hidden" name="nolivre" value="<?= (int)$nolivre ?>">
                <button type="submit" class="btn btn-outline-secondary">
                    Emprunter (ajout au panier)
                </button>
            </form>
        <?php else: ?>
    <span class="text-muted">
        <?= $inCart ? "Déjà ajouté au panier" : "Déjà emprunté" ?>
    </span>
<?php endif; ?>


    <?php endif; ?>




                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex align-items-start justify-content-center">
            <?php $photo = (!empty($livre['photo'])) ? $livre['photo'] : 'book-cover-placeholder.png'; ?>
            <img src="covers/<?= htmlspecialchars($photo) ?>"
                 alt="Couverture de <?= htmlspecialchars($livre['titre']) ?>"
                 class="img-fluid rounded"
                 style="max-height:420px;">
        </div>
    </div>
</div>

</body>
</html>
