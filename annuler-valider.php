<?php

session_start();
require "connexion.php";

// S'assurer que le panier existe bien dans la session
if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['mel'])) {
    header("Location: accueil.php");
    exit;
}

$mel = $_SESSION['mel'];

// Traitement des actions annuler et valider du panier
if (isset($_GET['annuler'])) {
    $id = (int)$_GET['annuler'];
    unset($_SESSION['panier'][(string)$id]);
    $_SESSION['flash_success'] = "Livre retiré du panier.";
    header("Location: panier.php");
    exit;
}

// Validation du panier
if (isset($_POST['valider'])) {
    $ids = array_map('intval', array_keys($_SESSION['panier']));
    $ids = array_values(array_filter($ids, fn($v) => $v > 0));

    if (empty($ids)) {
        $_SESSION['flash_warning'] = "Votre panier est vide.";
        header("Location: panier.php");
        exit;
    }

    // Compter les emprunts en cours
    $stmt = $connexion->prepare("SELECT COUNT(*) FROM emprunter WHERE mel = ? AND dateretour IS NULL");
    $stmt->execute([$mel]);
    $enCours = (int)$stmt->fetchColumn();

    if (($enCours + count($ids)) > 5) {
        $_SESSION['flash_warning'] = "Impossible : limite 5 dépassée (panier + emprunts en cours).";
        header("Location: panier.php");
        exit;
    }

    
    $connexion->beginTransaction();
    try {
        // Vérifier si un livre est déjà emprunté
        $check = $connexion->prepare("SELECT 1 FROM emprunter WHERE nolivre = ? AND dateretour IS NULL LIMIT 1");

        // Enregistrer un emprunt
        $insert = $connexion->prepare("INSERT INTO emprunter (mel, nolivre, dateemprunt, dateretour) VALUES (?, ?, NOW(), NULL)");

        // Parcourir les livres du panier
        foreach ($ids as $nolivre) {
            $check->execute([$nolivre]);
            $indispo = (bool)$check->fetchColumn();
            if ($indispo) {
                continue; 
            }
            $insert->execute([$mel, $nolivre]);
        }
        
        $connexion->commit();
        $_SESSION['panier'] = [];
        $_SESSION['flash_success'] = "Panier validé.";
    } catch (Exception $e) {
        $connexion->rollBack();
        $_SESSION['flash_warning'] = "Erreur validation panier : " . $e->getMessage();
    }

    // Retour sur la page panier
    header("Location: panier.php");
    exit;
}
?>