<?php

if (!empty($_SESSION['mel'])) {
    $nom = htmlspecialchars($_SESSION['nom'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $prenom = htmlspecialchars($_SESSION['prenom'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $mel = htmlspecialchars($_SESSION['mel'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $adresse = htmlspecialchars($_SESSION['adresse'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $ville = htmlspecialchars($_SESSION['ville'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $codepostal = htmlspecialchars($_SESSION['codepostal'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    ?>
    <div id="session-widget" class="profile-widget">
        <h2>Profil</h2>
        <div><strong>Nom :</strong> <?php echo $nom; ?></div>
        <div><strong>Prénom :</strong> <?php echo $prenom; ?></div>
        <div><strong>Email :</strong> <?php echo $mel; ?></div>
        <div><strong>Adresse :</strong> <?php echo $adresse; ?></div>
        <div><strong>Ville :</strong> <?php echo $ville; ?></div>
        <div><strong>Code postal :</strong> <?php echo $codepostal; ?></div>
        <form method="post" action="session.php" id="logout-form">
            <button type="submit" name="logout">Se déconnecter</button>
        </form>
    </div>
    <?php
    return;
}


?>
<div id="session-widget" class="login-widget">
  <h2>Se connecter</h2>
  <form id="login-form-widget" method="post" action="seconnecter.php">
    <label for="identifiant_w">Email</label>
    <input id="identifiant_w" name="identifiant" type="email" required>

    <label for="mdp_w">Mot de passe</label>
    <input id="mdp_w" name="mdp" type="password" required>

    <button type="submit">Connexion</button>
    <div id="login-error" style="color:#b00020;margin-top:8px;display:none"></div>
  </form>
</div>

<?php

