<?php require APPROOT . '/views/bases/header.php'; ?>
  <div class="row mb-3">
    <div class="col-md-6">
      <h1>Posts</h1>
    </div>
    <?php if(!empty($_SESSION['flashAdd'])){
                flash('flashAdd');
    } ?>
    <?php if(!empty($_SESSION['flashFailure'])){
                flash('flashFailure');
    } ?>
    <div class="col-md-6">
      <a href="<?php echo URLROOT; ?>/posts/addPost" class="btn btn-primary pull-right">
        <i class="fa fa-pencil"></i> Ajouter un post
      </a>
    </div>
  </div>

  <?php foreach($data['posts'] as $post) : ?>
    <!-- on affiche les posts -->
    <div class="card card-body mb-3">
      <!-- ici on affiche avec la syntaxe des objets en PHP car dans la requete on spécifie PDO::FETCH_OBJ -->
      <h4 class="card-title"><?= htmlspecialchars($post->title) ?></h4>
      <div class="bg-light p-2 mb-3">
        Publié par <?= htmlspecialchars($post->nom) ?> le <?= $post->dateCreated; ?>
      </div>
      <?php $allowed_tags = '<p><b><i><strong><em><span><ul><ol><li><br><hr>';?>
      <p class="card-text"><?= strip_tags(htmlspecialchars_decode($post->content), $allowed_tags)  ?></p>

      <!-- Conteneur des boutons -->
       <div class="d-flex justify-content-between align-items-center">
       <!-- redirection à faire sur la structure du router : controlerName/methodName/params -->
        <a href="<?php echo URLROOT; ?>/posts/details/<?php echo $post->postId; ?>" class="btn btn-dark">Voir plus</a>

        <!-- ici on affichera un bouton supprimer le post lié à son utilisateur , on il sera affiché uniquement pour le propriétaire -->
        <?php if($_SESSION['user_id'] == $post->id_user) : ?>
        <form action="<?php echo URLROOT; ?>/posts/delete/<?php echo $post->postId; ?>" method="POST" style="display:inline">
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>
        <?php endif; ?>
       </div>
    </div>
      

  <?php endforeach; ?>
<?php require APPROOT . '/views/bases/footer.php'; ?>

