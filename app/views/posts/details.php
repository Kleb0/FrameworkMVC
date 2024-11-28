<?php require APPROOT . '/views/bases/header.php'; ?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Retour</a>
<br>
<h1><?php echo $data['post']->title; ?></h1>
<div class="bg-secondary text-white p-2 mb-3">
    Publié par  
</div>
<?php $allowed_tags = '<p><b><i><strong><em><span><ul><ol><li><br><hr>';?>
<p class="card-text"><?= strip_tags(htmlspecialchars_decode($data['post']->content), $allowed_tags)  ?></p>
<?php if($data['post']->id_user == $_SESSION['user_id']) { ?>
<hr>
<div class="d-flex justify-content-between">
    <a href="<?php echo URLROOT; ?>/posts/update/<?=$data['post']->id?> " class="btn btn-dark">Modifier</a>
    <a href="<?php echo URLROOT; ?>/posts/delete/<?=$data['post']->id?> " class="btn btn-danger" onclick="return confirm('Voulez-vous supprimer ce post ?');">Supprimer</a>
</div>
<?php } ?>


<div class="comment-section">
  <h2>Commentaires</h2>
    <div class="comment">
      <div class="comment-header">
        <i class="fas fa-user-circle comment-icon"></i>
        <div class="comment-user">
          <div class="comment-author">Publié par :</div>
        </div>
      </div>
      <div class="comment-body">
        <p></p>
      </div>
    </div>
</div>


<!-- Formulaire pour ajouter un commentaire -->
<h3>Ajouter un commentaire</h3>

<form action="" method="POST">
  <div class="form-group">
    <textarea name="body" class="form-control" required></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Commenter</button>
</form>



<?php require APPROOT . '/views/bases/footer.php'; ?>