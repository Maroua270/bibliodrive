<?php
session_start();
require "connexion.php";




if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$mel = $_SESSION['mel'] ?? null;


$empruntsEnCours = 0;
if ($mel) {
    $stmt = $connexion->prepare("SELECT COUNT(*) FROM emprunter WHERE mel = ? AND dateretour IS NULL");
    $stmt->execute([$mel]);
    $empruntsEnCours = (int)$stmt->fetchColumn();
}

$cartCount = count($_SESSION['panier']);
$reste = max(0, 5 - ($empruntsEnCours + $cartCount));


$livres = [];
$ids = array_map('intval', array_keys($_SESSION['panier']));
$ids = array_values(array_filter($ids, fn($v) => $v > 0));

if (!empty($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT nolivre, titre, anneeparution FROM livre WHERE nolivre IN ($placeholders) ORDER BY titre";
    $stmt = $connexion->prepare($sql);
    $stmt->execute($ids);
    $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-panier">

    <img src="library.png" alt="Bibliothèque" width="150" height="100" class="position-fixed top-0 end-0 m-3">

<div class="container-fluid mt-4">
    <?php require_once "navbar.php"; ?>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_warning'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['flash_warning']) ?>
        </div>
        <?php unset($_SESSION['flash_warning']); ?>
    <?php endif; ?>

    <?php if (!isset($_SESSION['mel'])): ?>

    <h2 class="text-success text-center">Votre panier</h2>
    <div class="alert alert-secondary text-center mt-4">
        Connectez-vous pour voir votre panier.
    </div>

<?php else: ?>

    <h2 class="text-success text-center">Votre panier</h2>
    <p class="text-center text-primary">
        (encore <?= (int)$reste ?> réservation possible, <?= (int)$empruntsEnCours ?> emprunt en cours)
    </p>

    <div class="row justify-content-center">
        <div class="col-md-7">

            <?php if (empty($livres)): ?>
                <div class="alert alert-secondary text-center">
                    Votre panier est vide.
                </div>
            <?php else: ?>
                <div class="list-group mb-3">
                    <?php foreach ($livres as $l): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <?= htmlspecialchars($l['titre']) ?>
                                <?php if (!empty($l['anneeparution'])): ?>
                                    (<?= htmlspecialchars($l['anneeparution']) ?>)
                                <?php endif; ?>
                            </div>
                            <a class="btn btn-outline-secondary btn-sm"
                               href="annuler-valider.php?annuler=<?= (int)$l['nolivre'] ?>">
                                Annuler
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <form action="annuler-valider.php" method="post" class="text-center">
                    <button type="submit" name="valider" value="1" class="btn btn-outline-secondary">
                        Valider le panier
                    </button>
                </form>
            <?php endif; ?>

        </div>
    </div>

<?php endif; ?>

</div>

</body>
</html>
