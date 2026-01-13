<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$loginError = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

// URL de redirection après connexion
$redirect = $_SERVER['REQUEST_URI'] ?? 'accueil.php';
?>

<div class="login-panel">
    <?php if (!isset($_SESSION['mel'])): ?>
        <!-- Cas 1: utilisateur NON connecté => afficher le formulaire -->
        <h5 class="text-center mb-3">Se connecter</h5>

            <?php if ($loginError): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($loginError) ?></div>
            <?php endif; ?>

        <form action="seconnecter.php" method="post" novalidate>
            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

            <div class="mb-2">
                <label for="login-identifiant" class="form-label">Identifiant</label>
                <input type="email" class="form-control" id="login-identifiant" name="identifiant" placeholder="Votre email" required>
            </div>

            <div class="mb-3">
                <label for="login-password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="login-password" name="mdp" placeholder="Votre mot de passe" required>
            </div>

            <button type="submit" class="btn btn-outline-dark w-100">Connexion</button>
        </form>

    <?php else: ?>
        <!-- Cas 2: utilisateur connecté => afficher les infos et le bouton déconnexion -->
        <h5 class="text-center mb-3">Mon compte</h5>
        <p><strong>Nom :</strong> <?= htmlspecialchars($_SESSION['nom'] ?? '') ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($_SESSION['prenom'] ?? '') ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($_SESSION['mel'] ?? '') ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($_SESSION['adresse'] ?? '') ?></p>
        <p><strong>Ville :</strong> <?= htmlspecialchars($_SESSION['ville'] ?? '') ?></p>
        <p><strong>Code postal :</strong> <?= htmlspecialchars($_SESSION['codepostal'] ?? '') ?></p>
        <p><strong>Profil :</strong> <?= htmlspecialchars($_SESSION['profil'] ?? '') ?></p>

        <form action="logout.php" method="post">
            <button class="btn btn-danger w-100">Déconnexion</button>
        </form>
    <?php endif; ?>
</div>





