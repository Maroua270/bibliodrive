<?php

session_start();
require "connexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: accueil.php");
    exit;
}

// Récupération des champs du formulaire
$identifiant = trim($_POST['identifiant'] ?? '');
$mdp = $_POST['mdp'] ?? '';
$redirect = $_POST['redirect'] ?? 'accueil.php';


//on empêche une redirection vers un site externe
if (!is_string($redirect) || $redirect === '' || str_starts_with($redirect, 'http')) {
    $redirect = 'accueil.php';
}

if ($identifiant === '' || $mdp === '') {
    $_SESSION['login_error'] = "Veuillez remplir tous les champs.";
    header("Location: " . $redirect);
    exit;
}

// Recherche de l'utilisateur par email (mel)
$stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE mel = :mel LIMIT 1");
$stmt->execute([':mel' => $identifiant]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérification des identifiants (actuellement: comparaison en clair)
if (!$user || $user['motdepasse'] !== $mdp) {
    $_SESSION['login_error'] = "Identifiant ou mot de passe incorrect.";
    header("Location: " . $redirect);
    exit;
}


// Si ok: on stocke les infos utiles en session
$_SESSION['mel'] = $user['mel'];
$_SESSION['nom'] = $user['nom'];
$_SESSION['prenom'] = $user['prenom'];
$_SESSION['adresse'] = $user['adresse'];
$_SESSION['ville'] = $user['ville'];
$_SESSION['codepostal'] = $user['codepostal'];
$_SESSION['profil'] = $user['profil'];


// Si admin, on redirige vers la page d'administration
if (strtolower($user['profil']) === 'admin' || strtolower($user['profil']) === 'administrateur') {
    header("Location: admin.php");
    exit;
}

// Sinon, retour sur la page demandée
header("Location: " . $redirect);
exit;
