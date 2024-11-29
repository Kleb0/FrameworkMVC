<?php

class ArticlesCuration extends AbstractController {
    private $curationModel;

    public function __construct() {
        $this->curationModel = $this->model('ArticlesCurationModel');
    }

    // Affiche la liste des articles à curer
    public function index() {
        $articlesToCurate = $this->curationModel->getArticlesToCurate();

        $data = [
            'title' => 'Curation des articles',
            'articles' => $articlesToCurate,
        ];

        $this->render('index', $data);
    }

    // Met à jour le statut d'un article
    public function update($articleId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = htmlspecialchars($_POST['status']);
            $adminComments = htmlspecialchars($_POST['admin_comments']);

            if ($this->curationModel->updateCurationStatus($articleId, $status, $adminComments)) {
                flash('curation_message', 'Article mis à jour avec succès.', 'alert alert-success');
                redirect('articlesCuration/index');
            } else {
                flash('curation_message', 'Erreur lors de la mise à jour.', 'alert alert-danger');
                redirect('articlesCuration/index');
            }
        }
    }
}
