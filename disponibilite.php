<?php

require "connexion.php";
$nolivre = (int)($_GET['id'] ?? 0);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $connexion must exist (PDO) and $nolivre must exist
// Example in livre.php before include:
// require_once "connexion.php";
// $nolivre = (int)$_GET['id'];

$sql = "
    SELECT dateretour
    FROM emprunter
    WHERE nolivre = ?
    ORDER BY dateemprunt DESC
    LIMIT 1
";
$stmt = $connexion->prepare($sql);
$stmt->execute([$nolivre]);
$emprunt = $stmt->fetch(PDO::FETCH_ASSOC);

$isDisponible = !($emprunt && $emprunt['dateretour'] === null);
$disponibilite = $isDisponible ? "Disponible" : "Indisponible";
?>

<div class="d-flex align-items-center gap-3 mt-3">
    <!-- Disponibilité -->
    <span class="fw-bold <?= $isDisponible ? 'text-success' : 'text-danger' ?>">
        <?= htmlspecialchars($disponibilite) ?>
    </span>

    <?php if (!$isDisponible): ?>
        <span class="text-muted">Déjà emprunté</span>

    <?php elseif (!isset($_SESSION['mel'])): ?>
        <span class="border border-danger text-danger px-3 py-2 rounded">
            Pour pouvoir réserver vous devez posséder un compte et vous identifier.
        </span>

    <?php else: ?>
        <a href="emprunter.php?nolivre=<?= (int)$nolivre ?>" class="btn btn-success">
            Emprunter
        </a>
    <?php endif; ?>
</div>
