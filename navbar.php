<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">

<style>
.top-banner {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    padding: 12px 20px;
    font-size: 14px;
    border-bottom: 1px solid #ddd;
    z-index: 1000;
}

.navbar {
    position: fixed;
    top: 60px; 
    left: 50%;
    transform: translateX(-45%); 
    
    background-color: #2c3e50;
    padding: 10px 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    
    display: flex;
    justify-content: center;
    width: 70%;
    max-width: 900px;
    z-index: 999;
    border-radius: 8px;
}

.navbar .search-form {
    display: flex;
    gap: 8px;
    width: 100%;
}

.navbar .search-form input {
    flex-grow: 1;
    padding: 6px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.navbar .search-form button,
.navbar .search-form a {
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    text-decoration: none;
    color: #fff;
    font-weight: 500;
    cursor: pointer;
}

.navbar .search-form button {
    background-color: #28a745;
}

.navbar .search-form .icon-button {
    width: 40px;
    padding: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.navbar .search-form .icon-button svg {
    width: 18px;
    height: 18px;
}

.navbar .search-form a {
    background-color: #6c757d;
}

.page-offset {
    height: 150px;
}

@media (max-width: 768px) {
    .navbar {
        width: 95%;
        transform: translateX(-50%);
    }

    .navbar .search-form {
        flex-direction: column;
    }
}
</style>

</head>

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



