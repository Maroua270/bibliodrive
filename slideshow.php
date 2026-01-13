<?php
require_once __DIR__ . "/connexion.php";


$sql = "SELECT titre, photo, anneeparution
        FROM livre
        ORDER BY dateajout DESC
        LIMIT 3";

$stmt = $connexion->query($sql);
// Récupération des résultats sous forme de tableau
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);


// S'il y a moins de 3 livres en base, on complète avec des valeurs null
// pour que le carrousel ait toujours 3 slides
while (count($livres) < 3) {
    $livres[] = null;
}

// Image par défaut si aucun livre ou aucune photo
$placeholder = "book-cover-placeholder.png";
?>

<div class="last-books">

    <div class="css-carousel">

        <!-- Boutons radio utilisés pour contrôler les slides -->
        <input type="radio" name="slides" id="s1" checked>
        <input type="radio" name="slides" id="s2">
        <input type="radio" name="slides" id="s3">

        <div class="slides">
            <?php for ($i = 0; $i < 3; $i++): ?>
                <?php
                    // Récupération du livre courant
                    $livre = $livres[$i];
                    $titre = $livre['titre'] ?? "Aucun livre";
                    $annee = $livre['anneeparution'] ?? "";
                    $photo = $livre['photo'] ?? "";

                    
                    $src = $placeholder;
                    if (!empty($photo)) {
                        $src = $photo;
                    }
                ?>
                <div class="slide">
                    <div class="slide-card">
                        <!-- Image de couverture -->
                        <img src="covers/<?php echo htmlspecialchars($src); ?>"
                             alt="<?php echo htmlspecialchars($titre); ?>">
                        <!-- Texte sous l'image -->
                        <div class="slide-caption">
                            <div class="slide-title"><?php echo htmlspecialchars($titre); ?></div>
                            <!-- Année affichée seulement si elle existe -->
                            <?php if ($annee !== ""): ?>
                                <div class="slide-year">(<?php echo htmlspecialchars($annee); ?>)</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <!-- Points de navigation du carrousel -->
        <div class="dots">
            <label for="s1" class="dot"></label>
            <label for="s2" class="dot"></label>
            <label for="s3" class="dot"></label>
        </div>
    </div>
</div>
<script>
    // Script JavaScript pour faire défiler automatiquement les slide
  (function () {
    const radios = document.querySelectorAll('input[name="slides"]');
    if (!radios.length) return;
    let i = 0;
    setInterval(() => {
      i = (i + 1) % radios.length;
      radios[i].checked = true;
    }, 3000); 
  })();
</script>
