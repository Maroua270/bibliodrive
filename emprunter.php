<?php

session_start();
require_once "connexion.php";

if (!isset($_SESSION['mel'])) {
    header("Location: login.php");
    exit;
}

$mel = $_SESSION['mel'];




// Récupérer l'identifiant du livre
if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = []; 
}


// Récupérer l'identifiant du livre à ajouter
$sql = "SELECT 1 FROM emprunter WHERE nolivre = ? AND dateretour IS NULL LIMIT 1";
$stmt = $connexion->prepare($sql);
$stmt->execute([$nolivre]);
if ($stmt->fetchColumn()) {
    $_SESSION['panier_error'] = "Ce livre est indisponible (déjà emprunté).";
    header("Location: livre.php?id=" . $nolivre);
    exit;
}

// Compter les emprunts en cours de l'utilisateur
$sql = "SELECT COUNT(*) FROM emprunter WHERE mel = ? AND dateretour IS NULL";
$stmt = $connexion->prepare($sql);
$stmt->execute([$mel]);
$nbEmpruntsEnCours = (int)$stmt->fetchColumn();


$nbDansPanier = count($_SESSION['panier']);

// max 5 (panier + emprunts en cours)
if (($nbEmpruntsEnCours + $nbDansPanier) >= 5) {
    $_SESSION['panier_error'] = "Pas plus de 5 emprunts au total (panier + emprunts en cours).";
    header("Location: panier.php");
    exit;
}


// Ajouter au panier si pas déjà présent
if (!isset($_SESSION['panier'][$nolivre])) {
    $_SESSION['panier'][$nolivre] = true;
    $_SESSION['flash_panier'] = "Ajouté au panier.";
} else {
    $_SESSION['flash_panier'] = "Ce livre est déjà dans le panier.";
}

header("Location: panier.php");
exit;

