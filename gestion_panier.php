<?php
session_start();

// Si l'id est invalide, retour à l'accueil
$nolivre = (int)($_POST['nolivre'] ?? 0);
if ($nolivre <= 0) {
    header("Location: accueil.php");
    exit;
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}


if (count($_SESSION['panier']) >= 5) {
    $_SESSION['panier_message'] = "Maximum 5 livres dans le panier.";
    header("Location: panier.php");
    exit;
}


if (!in_array($nolivre, $_SESSION['panier'], true)) {
    $_SESSION['panier'][] = $nolivre;
}

$_SESSION['panier_message'] = "Ajouté au panier.";
header("Location: panier.php");
exit;
?>