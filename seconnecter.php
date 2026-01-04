<?php
// seconnecter.php
session_start();
require "connexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: accueil.php");
    exit;
}

$identifiant = trim($_POST['identifiant'] ?? '');
$mdp = $_POST['mdp'] ?? '';
$redirect = $_POST['redirect'] ?? 'accueil.php';

// Security: only allow local redirects (avoid http://evil.com)
if (!is_string($redirect) || $redirect === '' || str_starts_with($redirect, 'http')) {
    $redirect = 'accueil.php';
}

if ($identifiant === '' || $mdp === '') {
    $_SESSION['login_error'] = "Veuillez remplir tous les champs.";
    header("Location: " . $redirect);
    exit;
}

$stmt = $connexion->prepare("SELECT * FROM utilisateur WHERE mel = :mel LIMIT 1");
$stmt->execute([':mel' => $identifiant]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['motdepasse'] !== $mdp) {
    $_SESSION['login_error'] = "Identifiant ou mot de passe incorrect.";
    header("Location: " . $redirect);
    exit;
}

// ✅ success: store session
$_SESSION['mel'] = $user['mel'];
$_SESSION['nom'] = $user['nom'];
$_SESSION['prenom'] = $user['prenom'];
$_SESSION['adresse'] = $user['adresse'];
$_SESSION['ville'] = $user['ville'];
$_SESSION['codepostal'] = $user['codepostal'];
$_SESSION['profil'] = $user['profil'];

// ✅ redirect admin vs user
if (strtolower($user['profil']) === 'admin' || strtolower($user['profil']) === 'administrateur') {
    header("Location: admin.php");
    exit;
}

header("Location: " . $redirect);
exit;
