<?php
// emprunter.php (adds to SESSION cart)
session_start();
require_once "connexion.php";

if (!isset($_SESSION['mel'])) {
    header("Location: login.php");
    exit;
}

$mel = $_SESSION['mel'];

// Accept nolivre from GET (because your link uses emprunter.php?nolivre=XX)


// Init cart
if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = []; // store as associative array: [nolivre => true]
}

// 1) If book already borrowed by someone (dateretour IS NULL), refuse
$sql = "SELECT 1 FROM emprunter WHERE nolivre = ? AND dateretour IS NULL LIMIT 1";
$stmt = $connexion->prepare($sql);
$stmt->execute([$nolivre]);
if ($stmt->fetchColumn()) {
    $_SESSION['panier_error'] = "Ce livre est indisponible (déjà emprunté).";
    header("Location: livre.php?id=" . $nolivre);
    exit;
}

// 2) Count ongoing loans for this user in DB
$sql = "SELECT COUNT(*) FROM emprunter WHERE mel = ? AND dateretour IS NULL";
$stmt = $connexion->prepare($sql);
$stmt->execute([$mel]);
$nbEmpruntsEnCours = (int)$stmt->fetchColumn();

// 3) Count items already in cart
$nbDansPanier = count($_SESSION['panier']);

// 4) Max 5 total (loans + cart)
if (($nbEmpruntsEnCours + $nbDansPanier) >= 5) {
    $_SESSION['panier_error'] = "Pas plus de 5 emprunts au total (panier + emprunts en cours).";
    header("Location: panier.php");
    exit;
}

// 5) Add to cart (avoid duplicates)
if (!isset($_SESSION['panier'][$nolivre])) {
    $_SESSION['panier'][$nolivre] = true;
    $_SESSION['flash_panier'] = "Ajouté au panier.";
} else {
    $_SESSION['flash_panier'] = "Ce livre est déjà dans le panier.";
}

// Send user to the cart (or back to book page if you prefer)
header("Location: panier.php");
exit;
