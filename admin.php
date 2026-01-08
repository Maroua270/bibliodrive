<?php
session_start();
require_once "connexion.php";


// Protection
if (!isset($_SESSION['mel'])) {
   
    header("Location: accueil.php");
    exit;
}

$profil = strtolower((string)($_SESSION['profil'] ?? ''));
if ($profil !== 'admin' && $profil !== 'administrateur') {
    header("Location: accueil.php");
    exit;
}

$page = $_GET['page'] ?? ''; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administration — Biblio-Drive</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="page-with-login">

<img src="library.png" alt="Bibliothèque" width="150" height="100" class="position-fixed top-0 end-0 m-3">

<div class="container-fluid mt-3">
    <p class="mb-3">
        La bibliothèque de Moulinsart est fermée au public jusqu'à nouvel ordre.
        Mais vous pouvez réserver et retirer vos livres via <strong>Biblio Drive</strong>.
    </p>
    
    <div class="mt-4">
        <h1 class="display-5">Administration</h1>
        <?php if ($page === ''): ?>
            <p class="text-muted">Choisis une action dans la barre ci-dessus.</p>
        <?php endif; ?>
    </div>

    
    <div class="bg-dark p-3 rounded-3 d-inline-flex gap-3 align-items-center" style="min-width: 520px;">
        <a href="admin.php?page=ajout" class="btn btn-light fw-semibold">Ajouter un livre</a>
        <a href="admin.php?page=membre" class="btn btn-light fw-semibold">Créer un membre</a>
    </div>

    
    <div class="mt-4" style="max-width: 900px;">
        <?php
        if ($page === 'ajout') {
            require "ajout_livre.php";
        } elseif ($page === 'membre') {
            require "creation_membre.php";
        }
        ?>
    </div>
</div>


<?php require "login.php"; ?>

</body>
</html>
  

    
