<?php
session_start();

$nolivre = (int)($_POST['nolivre'] ?? 0);
if ($nolivre <= 0) {
    header("Location: accueil.php");
    exit;
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// max 5 books
if (count($_SESSION['panier']) >= 5) {
    $_SESSION['panier_message'] = "Maximum 5 livres dans le panier.";
    header("Location: panier.php");
    exit;
}

// avoid duplicates
if (!in_array($nolivre, $_SESSION['panier'], true)) {
    $_SESSION['panier'][] = $nolivre;
}

$_SESSION['panier_message'] = "Ajouté au panier.";
header("Location: panier.php");
exit;
?>