<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="navbar.css">


<body>
<div class="top-banner">
    La bibliothèque de Moulinsart est fermée au public jusqu'à nouvel ordre.
    Mais vous pouvez réserver et retirer vos livres via
    <strong>Biblio Drive</strong>.
</div>

<nav class="navbar">
    <form class="search-form" action="recherche.php" method="get">
        <input
            name="author"
            type="text"
            placeholder="Rechercher dans le catalogue"
            value="<?= isset($_GET['author']) ? htmlspecialchars($_GET['author']) : '' ?>"
        >
        <button type="submit" class="icon-button" aria-label="Rechercher" title="Rechercher">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
        <a href="accueil.php">Accueil</a>
        <a href="panier.php">Panier</a>
    </form>
</nav>

<div class="page-offset"></div>
<?php include_once "login.php"; ?>


<div class="rgpd-info">
  Cookies nécessaires
  <div class="rgpd-tooltip">
    Ce site utilise uniquement des cookies nécessaires au fonctionnement
    (connexion, panier). Aucun cookie tiers n’est utilisé.
  </div>
</div>



</body>