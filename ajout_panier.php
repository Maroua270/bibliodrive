<?php

session_start();
require "connexion.php";

// Récupérer l'identifiant du livre
$nolivre = isset($_GET['nolivre']) ? (int)$_GET['nolivre'] : 0;
if ($nolivre <= 0) {
    header("Location: accueil.php");
    exit;
}


if (!isset($_SESSION['mel'])) {
    $_SESSION['flash_warning'] = "Pour pouvoir réserver vous devez posséder un compte et vous identifier.";
    header("Location: livre.php?id=" . $nolivre);
    exit;
}

$mel = $_SESSION['mel'];

// Vérifier si le livre est disponible (pas déjà emprunté)
$stmt = $connexion->prepare("SELECT 1 FROM emprunter WHERE nolivre = ? AND dateretour IS NULL LIMIT 1");
$stmt->execute([$nolivre]);
$isDisponible = !$stmt->fetchColumn();

if (!$isDisponible) {
    $_SESSION['flash_warning'] = "Livre indisponible (déjà emprunté).";
    header("Location: livre.php?id=" . $nolivre);
    exit;
}

// Compter le nombre d'emprunts en cours de l'utilisateur (dateretour NULL)
$stmt = $connexion->prepare("SELECT COUNT(*) FROM emprunter WHERE mel = ? AND dateretour IS NULL");
$stmt->execute([$mel]);
$enCours = (int)$stmt->fetchColumn();


if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}


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


$_SESSION['panier'][(string)$nolivre] = true;
$_SESSION['flash_success'] = "Ajouté au panier.";

// Retour sur la page du livre
header("Location: livre.php?id=" . $nolivre);
exit;
