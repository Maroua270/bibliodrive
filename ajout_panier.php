<?php
// ajout_panier.php
session_start();
require "connexion.php";

$nolivre = isset($_GET['nolivre']) ? (int)$_GET['nolivre'] : 0;
if ($nolivre <= 0) {
    header("Location: accueil.php");
    exit;
}

// must be logged in to add
if (!isset($_SESSION['mel'])) {
    $_SESSION['flash_warning'] = "Pour pouvoir réserver vous devez posséder un compte et vous identifier.";
    header("Location: livre.php?id=" . $nolivre);
    exit;
}

$mel = $_SESSION['mel'];

// check availability: not available if there is an ongoing loan (dateretour IS NULL)
$stmt = $connexion->prepare("SELECT 1 FROM emprunter WHERE nolivre = ? AND dateretour IS NULL LIMIT 1");
$stmt->execute([$nolivre]);
$isDisponible = !$stmt->fetchColumn();

if (!$isDisponible) {
    $_SESSION['flash_warning'] = "Livre indisponible (déjà emprunté).";
    header("Location: livre.php?id=" . $nolivre);
    exit;
}

// count current ongoing loans for this user
$stmt = $connexion->prepare("SELECT COUNT(*) FROM emprunter WHERE mel = ? AND dateretour IS NULL");
$stmt->execute([$mel]);
$enCours = (int)$stmt->fetchColumn();

// init cart
if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// max 5 total (ongoing + cart)
$cartCount = count($_SESSION['panier']);
if (($enCours + $cartCount) >= 5 && !isset($_SESSION['panier'][(string)$nolivre])) {
    $_SESSION['flash_warning'] = "Limite atteinte : 5 emprunts/réservations maximum (panier + emprunts en cours).";
    header("Location: livre.php?id=" . $nolivre);
    exit;
}
if (isset($_SESSION['panier'][(string)$nolivre])) {
    $_SESSION['flash_warning'] = "Ce livre est déjà dans votre panier.";
    header("Location: livre.php?id=" . $nolivre);
    exit;
}

// add to cart
$_SESSION['panier'][(string)$nolivre] = true;
$_SESSION['flash_success'] = "Ajouté au panier.";

header("Location: livre.php?id=" . $nolivre);
exit;
