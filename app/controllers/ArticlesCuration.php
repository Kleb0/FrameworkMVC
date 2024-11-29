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

    public function showArticleToBeCurated($id) {
        // Vérifie que l'ID est valide
        if (!is_numeric($id)) {
            flash('curation_message', 'ID invalide.', 'alert alert-danger');
            redirect('articlesCuration/index');
        }
    
        $article = $this->curationModel->getArticleFromTableArticlesToPass($id);
    
        // if ($article) {
        //     // Traitement de l'article récupéré
        //     echo 'Titre de l\'article : ' . htmlspecialchars($article->title);
        // } else {
        //     echo 'Aucun article trouvé avec cet ID.';
        // }
    
        // // Vérification du contenu de l'article
        // echo '<pre>';
        // var_dump($article); // Affiche les données de l'article pour déboguer
        // echo '</pre>';
    
        // Prépare les données pour la vue
        $data = [
            'title' => 'Vérification de l\'article',
            'article_id' => $id,
            'article' => $article,  
        ];
    
        // Affiche la vue
        $this->render('showArticleToBeCurated', $data);
    }
    
    
    

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


