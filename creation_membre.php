<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "connexion.php";

$profil = strtolower((string)($_SESSION['profil'] ?? ''));
if ($profil !== 'admin' && $profil !== 'administrateur') {
    echo '<div class="alert alert-danger">Accès refusé.</div>';
    return;
}

$errors = [];
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'creation_membre') {
    $mel = trim($_POST['mel'] ?? '');
    $motdepasse = trim($_POST['motdepasse'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $codepostal = trim($_POST['codepostal'] ?? '');

    if ($mel === '' || $motdepasse === '' || $nom === '' || $prenom === '') {
        $errors[] = "Mel, mot de passe, nom, prénom sont obligatoires.";
    }

    if (!$errors) {
        // mel unique ?
        $stmt = $connexion->prepare("SELECT 1 FROM utilisateur WHERE mel = ? LIMIT 1");
        $stmt->execute([$mel]);
        if ($stmt->fetchColumn()) {
            $errors[] = "Ce mel existe déjà.";
        }
    }

    if (!$errors) {
        $sql = "INSERT INTO utilisateur (mel, motdepasse, nom, prenom, adresse, ville, codepostal, profil)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'client')";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$mel, $motdepasse, $nom, $prenom, $adresse, $ville, $codepostal]);
        $ok = true;
    }
}
?>

<div class="card">
    <div class="card-body">
        <h3 class="text-danger text-center mb-4">Créer un membre</h3>

        <?php if ($ok): ?>
            <div class="alert alert-success">Membre créé.</div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars(implode(" ", $errors)) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="row g-3">
            <input type="hidden" name="action" value="creation_membre">

            <div class="col-12">
                <label class="form-label fw-semibold">Mel</label>
                <input type="email" name="mel" class="form-control" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Mot de passe</label>
                <input name="motdepasse" class="form-control" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Nom</label>
                <input name="nom" class="form-control" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Prénom</label>
                <input name="prenom" class="form-control" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Adresse</label>
                <input name="adresse" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Ville</label>
                <input name="ville" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Code Postal</label>
                <input name="codepostal" class="form-control">
            </div>

            <div class="col-12 text-center">
                <button class="btn btn-outline-dark px-5">Créer un membre</button>
            </div>
        </form>
    </div>
</div>
