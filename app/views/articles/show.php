<?php require APPROOT . '/views/bases/header.php'; ?>

<div class="container mt-5">
        
    <h1 class="mb-4"><?= cleanText($data['title']); ?></h1>

    <div class="card">
        <div class="card-body">
            <!-- Titre de l'article -->
            <?php if (isset($data['article']->title)): ?>
                <h5 class="card-title"><?= cleanText($data['article']->title); ?></h5>
            <?php else: ?>
                <p>Titre de l'article non défini.</p>
            <?php endif; ?>

            <!-- Contenu global de l'article -->
            <div class="mt-3">
                <p><?= cleanText($data['article']->content); ?></p>
            </div>

            <hr>

            <!-- Affichage des paragraphes avec leurs titres, textes et images -->
            <?php 
                // Décodage des champs JSON en tableaux PHP
                $paragraphTitles = json_decode($data['article']->paragraph_titles, true);
                $paragraphs = json_decode($data['article']->paragraphs, true);
                $paragraphImages = json_decode($data['article']->paragraph_images, true);
            ?>

        <?php if (!empty($paragraphTitles) && !empty($paragraphs)): ?>
            <?php foreach ($paragraphTitles as $index => $title): ?>
                <h4><?= cleanText($title); ?></h4>
                <p><?= isset($paragraphs[$index]) ? cleanText($paragraphs[$index]) : 'Paragraphe manquant.'; ?></p>

                <?php if (!empty($paragraphImages[$index])): ?>
                    <section class="paragraph-images-section mt-3">
                        <h5>Images associées :</h5>
                        <div class="paragraph-images mb-3">
                            <?php foreach ($paragraphImages[$index] as $image): ?>
                                <img src="<?= cleanText($image); ?>" alt="Image du paragraphe" class="img-fluid mb-2" style="max-width: 100%; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php else: ?>
                    <p>Aucune image disponible pour ce paragraphe.</p>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun contenu disponible pour les paragraphes.</p>
        <?php endif; ?>

        </div>
    </div>

    <!-- Bouton de retour -->
    <a href="<?= URLROOT ?>/articles/index" class="btn btn-primary mt-4">Retour</a>
</div>

<?php require APPROOT . '/views/bases/footer.php'; ?>

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
