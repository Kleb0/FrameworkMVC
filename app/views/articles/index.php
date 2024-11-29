<?php require APPROOT . '/views/bases/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4"><?= cleanText($data['title']) ?></h1>

    <!-- Message de confirmation -->
    <?php flash('article_message'); ?>

    <!-- Bouton pour créer un nouvel article -->
    <a href="<?= URLROOT ?>/articles/create" class="btn btn-primary mb-4">Créer un article</a>

    <!-- Liste des articles -->
    <?php if (!empty($data['articles'])): ?>
        <?php foreach ($data['articles'] as $article): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Titre de l'article -->
                    <h5 class="card-title"><?= cleanText($article->title); ?></h5>

                    <!-- Affichage du premier titre de paragraphe (décodé et nettoyé) -->
                    <?php 
                    $paragraphTitles = json_decode($article->paragraph_titles, true); 
                    $previewTitle = $paragraphTitles[0] ?? ''; // Récupère le premier titre s'il existe
                    ?>
                    <p class="card-text">
                        <?= cleanText($previewTitle); ?>
                    </p>

                    <!-- Bouton Lire plus -->
                    <a href="<?= URLROOT ?>/articles/show/<?= $article->id ?>" class="btn btn-secondary">Lire plus</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun article disponible.</p>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/bases/footer.php'; ?>

<?php
// Fonction de nettoyage du texte
function cleanText($text) {
    // Étape 1 : Décoder toutes les entités HTML (ex : &amp;, &lt;, &gt;, &#039;)
    $decodedText = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Étape 2 : Supprimer les balises HTML indésirables, en gardant les balises autorisées
    $allowedTags = '<b><i><u><strong><em>';
    $cleanedText = strip_tags($decodedText, $allowedTags);

    // Étape 3 : Supprimer les espaces multiples et insécables
    $cleanedText = preg_replace('/\s+/', ' ', $cleanedText);

    // Étape 4 : Retourner le texte propre
    return trim($cleanedText);
}
?>
