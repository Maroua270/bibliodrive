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


if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}


$_SESSION['panier'][(string)$nolivre] = true;
$_SESSION['flash_success'] = "Ajouté au panier.";

// Retour sur la page du livre
header("Location: livre.php?id=" . $nolivre);
exit;


