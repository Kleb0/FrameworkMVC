<?php require APPROOT . '/views/bases/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4"><?= cleanText($data['title']); ?></h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= cleanText($data['article']->title); ?></h5>

            <!-- Affichage des paragraphes -->
            <?php 
            $paragraphTitles = json_decode($data['article']->paragraph_titles, true); 
            $paragraphs = json_decode($data['article']->paragraphs, true); 
            ?>

            <?php if ($paragraphTitles && $paragraphs): ?>
                <?php foreach ($paragraphTitles as $index => $title): ?>
                    <!-- Utilisation de cleanText pour les titres -->
                    <h6><?= cleanText($title); ?></h6>
                    <!-- Utilisation de cleanText pour les paragraphes -->
                    <p><?= cleanText($paragraphs[$index]); ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Contenu global -->
            <div class="mt-3">
                <?= cleanText($data['article']->content); ?>
            </div>
        </div>
    </div>

    <!-- Bouton retour -->
    <a href="<?= URLROOT ?>/articles/index" class="btn btn-primary mt-4">Retour</a>
</div>

<?php
// Fonction de nettoyage du texte
function cleanText($text) {
    // Décoder les entités HTML (ex: &nbsp;, &lt;, &gt;, &#039;)
    $decodedText = htmlspecialchars_decode($text, ENT_QUOTES | ENT_HTML5);

    // Supprimer les balises HTML indésirables, mais autoriser les balises essentielles (ex: images)
    $allowedTags = '<p><b><i><u><strong><em><img>';
    $cleanedText = strip_tags($decodedText, $allowedTags);

    // Supprimer les espaces multiples ou insécables
    $cleanedText = preg_replace('/\s+/', ' ', $cleanedText); // Remplace les espaces multiples par un seul espace

    // Retourner le texte nettoyé
    return trim($cleanedText);
}
?>

<?php require APPROOT . '/views/bases/footer.php'; ?>
