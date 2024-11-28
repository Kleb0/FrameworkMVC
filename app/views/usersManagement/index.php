<?php require APPROOT . '/views/bases/header.php'; ?>

<h1 class="mb-4"><?= $data['title'] ?></h1>

<div class="container">
    <div class="row">
        <?php foreach ($data['users'] as $user): ?>
            <?php if (isset($_SESSION['email']) && $user->email === $_SESSION['email']) continue; ?>
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title"><?= htmlspecialchars($user->nom); ?></h5>
                            <p class="card-text">
                                Email: <?= htmlspecialchars($user->email); ?><br>
                                Rôle: <?= htmlspecialchars($user->role_name); ?><br>
                                Id : <?= htmlspecialchars($user->id); ?>
                            </p>
                        </div>
                        <!-- Bouton pour ouvrir une fenêtre modale -->
                        <button
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#editRoleModal-<?= $user->id ?>"
                        >
                            Modifier le rôle
                        </button>

                        <!-- Fenêtre modale -->
                        <div class="modal fade" id="editRoleModal-<?= $user->id ?>" tabindex="-1" aria-labelledby="editRoleModalLabel-<?= $user->id ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="<?= URLROOT ?>/usersManagement/editRole/<?= $user->id ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editRoleModalLabel-<?= $user->id ?>">Modifier le rôle</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="role-<?= $user->id ?>" class="form-label">Rôle de l'utilisateur</label>
                                                <select name="role_id" id="role-<?= $user->id ?>" class="form-select">
                                                    <option value="1" <?= $user->role == 1 ? 'selected' : '' ?>>Utilisateur</option>
                                                    <option value="2" <?= $user->role == 2 ? 'selected' : '' ?>>Administrateur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-success">Modifier</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <a href="<?= URLROOT ?>/usersManagement/delete/<?= $user->id ?>" class="btn btn-danger" 
                           onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">Supprimer</a>
                    </div>
                </div>
            </div>           

        <?php endforeach; ?>
    </div>
</div>



<?php require APPROOT . '/views/bases/footer.php'; ?>
