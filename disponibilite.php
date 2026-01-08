<?php

require "connexion.php";
$nolivre = (int)($_GET['id'] ?? 0);


// Démarre la session si elle n'est pas déjà démarrée (pour savoir si l'utilisateur est connecté)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


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


// Disponible si:
// - aucun emprunt trouvé
// OU
// - le dernier emprunt a une dateretour (donc le livre a été rendu)
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
