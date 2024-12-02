<?php require APPROOT . '/views/bases/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4"><?= cleanText($data['title']); ?></h1>

    <!-- Afficher l'ID de l'article à vérifier -->
    <p>id de l'article à vérifier : <?= $data['article_id']; ?></p>

    <!-- en fait à partir d'ici on arrive pas à passer l'article à vérifier -->

    <div class="card">
        <div class="card-body">
            <?php if (isset($data['article']->title) && !empty($data['article']->title)): ?>
                <h5 class="card-title"><?= cleanText($data['article']->title); ?></h5>
            <?php else: ?>
                <p>Titre de l'article non défini.</p>
            <?php endif; ?>

            <!-- Affichage des paragraphes -->
            <?php 
                // Vérification et décodage des titres et paragraphes
                $paragraphTitles = is_array($data['article']->paragraph_titles) 
                    ? $data['article']->paragraph_titles 
                    : json_decode($data['article']->paragraph_titles, true);

                $paragraphs = is_array($data['article']->paragraphs) 
                    ? $data['article']->paragraphs 
                    : json_decode($data['article']->paragraphs, true); 
            ?>

            <?php if ($paragraphTitles && $paragraphs): ?>
                <?php foreach ($paragraphTitles as $index => $title): ?>
                    <h6><?= cleanText($title); ?></h6>
                    <p><?= cleanText($paragraphs[$index]); ?></p>

                    <!-- Affichage des images pour chaque paragraphe -->
                    <?php 
                    $images = json_decode($data['article']->paragraph_images, true)[$index] ?? []; 
                    if (!empty($images)): 
                    ?>
                        <div class="paragraph-images">
                            <?php foreach ($images as $image): ?>
                                <img src="<?= $image ?>" alt="Image du paragraphe" class="img-fluid">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>


            <!-- Affichage du contenu global -->
            <div class="mt-3">
                <?= cleanText($data['article']->content); ?>
            </div>

            <!-- bouton de validation -->
            <form action="<?= URLROOT ?>/articlesCuration/validateArticle" method="post">
                <input type="hidden" name="article_id" value="<?= $data['article_id']; ?>">
                <button type="submit" class="btn btn-success mt-3">Valider l'article</button>
            </form>


        </div>
    </div>

    <!-- Bouton retour -->
    <a href="<?= URLROOT ?>/articlesCuration/index" class="btn btn-primary mt-4">Retour</a>
</div>

<?php
// Fonction de nettoyage du texte
function cleanText($text) {
    $decodedText = htmlspecialchars_decode($text, ENT_QUOTES | ENT_HTML5);
    $allowedTags = '<p><b><i><u><strong><em><img>';
    $cleanedText = strip_tags($decodedText, $allowedTags);
    $cleanedText = preg_replace('/\s+/', ' ', $cleanedText); 
    return trim($cleanedText);
}
?>

<?php require APPROOT . '/views/bases/footer.php'; ?>
