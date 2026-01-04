<?php
require_once __DIR__ . "/connexion.php";

// 3 derniers livres ajoutés
$sql = "SELECT titre, photo, anneeparution
        FROM livre
        ORDER BY dateajout DESC
        LIMIT 3";

$stmt = $connexion->query($sql);
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sécurité: si moins de 3 livres, on complète avec null
while (count($livres) < 3) {
    $livres[] = null;
}

$placeholder = "book-cover-placeholder.png";
?>

<div class="last-books">

    <div class="css-carousel">

        <!-- radios -->
        <input type="radio" name="slides" id="s1" checked>
        <input type="radio" name="slides" id="s2">
        <input type="radio" name="slides" id="s3">

        <div class="slides">
            <?php for ($i = 0; $i < 3; $i++): ?>
                <?php
                    $livre = $livres[$i];
                    $titre = $livre['titre'] ?? "Aucun livre";
                    $annee = $livre['anneeparution'] ?? "";
                    $photo = $livre['photo'] ?? "";

                    // si pas de photo => placeholder
                    $src = $placeholder;
                    if (!empty($photo)) {
                        $src = $photo;
                    }
                ?>
                <div class="slide">
                    <div class="slide-card">
                        <img src="covers/<?php echo htmlspecialchars($src); ?>"
                             alt="<?php echo htmlspecialchars($titre); ?>">
                        <div class="slide-caption">
                            <div class="slide-title"><?php echo htmlspecialchars($titre); ?></div>
                            <?php if ($annee !== ""): ?>
                                <div class="slide-year">(<?php echo htmlspecialchars($annee); ?>)</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <!-- dots -->
        <div class="dots">
            <label for="s1" class="dot"></label>
            <label for="s2" class="dot"></label>
            <label for="s3" class="dot"></label>
        </div>
    </div>
</div>
<script>
  (function () {
    const radios = document.querySelectorAll('input[name="slides"]');
    if (!radios.length) return;
    let i = 0;
    setInterval(() => {
      i = (i + 1) % radios.length;
      radios[i].checked = true;
    }, 3000); // 3 seconds
  })();
</script>
