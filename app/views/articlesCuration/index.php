<?php require APPROOT . '/views/bases/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4"><?= $data['title']; ?></h1>

    <?php flash('curation_message'); ?>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Date de soumission</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data['articles'])): ?>
                <?php foreach ($data['articles'] as $article): ?>
                    <tr>
                        <td><?= $article->article_id; ?></td>
                        <td><?= $article->title; ?></td>
                        <td><?= $article->submitted_by; ?></td>
                        <td><?= $article->submission_date; ?></td>
                        <td><?= ucfirst($article->status); ?></td>
                        <td>
                            <a href="<?= URLROOT ?>/articlesCuration/showArticleToBeCurated/<?= $article->article_id ?>" class="btn btn-primary btn-sm">Vérifier_2</a>
                            <!-- <a href="<?= URLROOT ?>/curation/showcuration/<?= $article->article_id ?>" class="btn btn-primary btn-sm">Vérifier</a> -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Aucun article en attente de validation.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require APPROOT . '/views/bases/footer.php'; ?>
