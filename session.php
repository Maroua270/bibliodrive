<?php
session_start();
var_dump($_SESSION['profil'] ?? 'NO_PROFIL_IN_SESSION');
exit;


if (($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) || (isset($_GET['logout']))) {
    // Vide toutes les données de la session
    $_SESSION = [];
    // Supprime le cookie de session si les cookies sont utilisés
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
    header('Location: login.php');
    exit;
}

// Sécurisation des données avant affichage (protection XSS)
if (!empty($_SESSION['mel'])) {
    $nom = htmlspecialchars($_SESSION['nom'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $prenom = htmlspecialchars($_SESSION['prenom'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $mel = htmlspecialchars($_SESSION['mel'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $adresse = htmlspecialchars($_SESSION['adresse'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $ville = htmlspecialchars($_SESSION['ville'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $codepostal = htmlspecialchars($_SESSION['codepostal'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    ?>
    <!doctype html>
    <html lang="fr">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <title>Profil</title>
      <link rel="stylesheet" href="style.css">
      <style>
        .profile{max-width:700px;margin:28px auto;padding:18px;background:#fff;border-radius:8px}
        .field{margin:8px 0}
        .label{font-weight:700}
        .logout{background:#e53935;color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer}
      </style>
    </head>
    <body>
      <div class="profile">
        <!-- Affichage des informations utilisateur -->
        <h1>Profil</h1>
        <div class="field"><span class="label">Nom :</span> <?php echo $nom; ?></div>
        <div class="field"><span class="label">Prénom :</span> <?php echo $prenom; ?></div>
        <div class="field"><span class="label">Email :</span> <?php echo $mel; ?></div>
        <div class="field"><span class="label">Adresse :</span> <?php echo $adresse; ?></div>
        <div class="field"><span class="label">Ville :</span> <?php echo $ville; ?></div>
        <div class="field"><span class="label">Code postal :</span> <?php echo $codepostal; ?></div>

        <!-- Formulaire de déconnexion -->
        <form method="post" style="margin-top:14px">
          <button type="submit" name="logout" class="logout">Se déconnecter</button>
        </form>
      </div>
    </body>
    </html>
    <?php
    exit;
}


$err = '';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Se connecter</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
  <div class="login-panel">
    <h1>Se connecter</h1>
    <?php if ($err): ?><p class ="error"><?php echo htmlspecialchars($err); ?></p><?php endif; ?>
      
      <!-- Formulaire de connexion -->
    <form method="post" action="seconnecter.php">
      <label for="identifiant">Identifiant (email)</label>
      <input id="identifiant" name="identifiant" type="email" required>

      <label for="motdepasse">Mot de passe</label>
      <input id="motdepasse" name="mdp" type="password" required>

      <button type="submit">Connexion</button>
    </form>
  </div>
</body>
</html>
