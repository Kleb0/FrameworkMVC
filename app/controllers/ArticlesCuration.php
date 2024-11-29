<?php

class ArticlesCuration extends AbstractController {
    private $curationModel;

    public function __construct() {
        $this->curationModel = $this->model('ArticlesCurationModel');
    }

    public function index() {
        $articlesToCurate = $this->curationModel->getArticlesToCurate();

        $data = [
            'title' => 'Curation des articles',
            'articles' => $articlesToCurate,
        ];

        $this->render('index', $data);
    }

    // Afficher un article à vérifier
    public function showArticleToBeCurated() {
        $data = [
            'title' => 'Vérification de l\'article',
        ];
        $this->render('showArticleToBeCurated', $data);

    }

    // Met à jour le statut d'un article
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = htmlspecialchars($_POST['status']);
            $adminComments = htmlspecialchars($_POST['admin_comments'] ?? '');

            if ($this->curationModel->updateCurationStatus($id, $status, $adminComments)) {
                flash('curation_message', 'Statut mis à jour avec succès.', 'alert alert-success');
                redirect('articlesCuration/index');
            } else {
                flash('curation_message', 'Erreur lors de la mise à jour du statut.', 'alert alert-danger');
                redirect('articlesCuration/index');
            }
        } else {
            redirect('articlesCuration/index');
        }
    }
}

