<?php

if (session_status() === PHP_SESSION_NONE) session_start();
require_once "connexion.php";

// Vérification des droits: seul un admin peut voir/ utiliser ce formulaire
$profil = strtolower((string)($_SESSION['profil'] ?? ''));
if ($profil !== 'admin' && $profil !== 'administrateur') {
    echo '<div class="alert alert-danger">Accès refusé.</div>';
    return;
}


$auteurs = $connexion->query("SELECT noauteur, nom, prenom FROM auteur ORDER BY nom, prenom")->fetchAll();

$errors = [];
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajout_livre') {
    $noauteur = (int)($_POST['noauteur'] ?? 0);
    $titre = trim($_POST['titre'] ?? '');
    $isbn13 = trim($_POST['isbn13'] ?? '');
    $annee = trim($_POST['anneeparution'] ?? '');
    $detail = trim($_POST['detail'] ?? '');
    $photo = trim($_POST['photo'] ?? '');

    if ($noauteur <= 0) $errors[] = "Auteur obligatoire.";
    if ($titre === '') $errors[] = "Titre obligatoire.";
    if ($isbn13 === '') $errors[] = "ISBN13 obligatoire.";

    if (!$errors) {
        $sql = "INSERT INTO livre (noauteur, titre, isbn13, anneeparution, detail, dateajout, photo)
                VALUES (?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$noauteur, $titre, $isbn13, ($annee === '' ? null : $annee), $detail, $photo]);
        $ok = true;
    }
}
?>

<div class="card">
    <div class="card-body">
        <h3 class="text-danger text-center mb-4">Ajouter un livre</h3>

        <?php if ($ok): ?>
            <div class="alert alert-success">Livre ajouté.</div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars(implode(" ", $errors)) ?>
            </div>
        <?php endif; ?>

        <form method="post" class="row g-3">
            <input type="hidden" name="action" value="ajout_livre">

            <div class="col-12">
                <label class="form-label fw-semibold">Auteur</label>
                <select name="noauteur" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($auteurs as $a): ?>
                        <option value="<?= (int)$a['noauteur'] ?>">
                            <?= htmlspecialchars($a['nom'] . ' ' . $a['prenom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Titre</label>
                <input name="titre" class="form-control" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">ISBN13</label>
                <input name="isbn13" class="form-control" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Année de parution</label>
                <input name="anneeparution" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Résumé</label>
                <textarea name="detail" class="form-control" rows="5"></textarea>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Image (nom fichier)</label>
                <input name="photo" class="form-control" placeholder="nomimage.jpg">
            </div>

            <div class="col-12 text-center">
                <button class="btn btn-outline-dark px-5">Ajouter</button>
            </div>
        </form>
    </div>
</div>
