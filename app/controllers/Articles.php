<?php

class Articles extends AbstractController {
    private $articleModel;

    public function __construct() {
        $this->articleModel = $this->model('ArticlesModel');
    }

    public function index() {
        $articles = $this->articleModel->getAllArticles();
        $data = [
            'title' => 'Articles',
            'articles' => $articles,
        ];
        $this->render('index', $data);
    }

    // Affiche le formulaire de création d'un article
    public function create() {
        $data = [
            'title' => 'Créer un article',
        ];
        $this->render('create', $data);
    }

    // Sauvegarde l'article
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Extraire les images des paragraphes
            $images = $this->extractImages(array_merge(
                [$_POST['content']], // Inclure le contenu global
                $_POST['paragraphs'] // Inclure tous les paragraphes
            ));

            $data = [
                'user_id' => $_SESSION['user_id'], 
                'title' => htmlspecialchars($_POST['title']),
                'paragraph_titles' => $_POST['paragraph_titles'], 
                'paragraphs' => $_POST['paragraphs'],             
                'content' => htmlspecialchars($_POST['content']),
                'images' => $images, // Ajouter les images extraites
            ];

            if ($this->articleModel->saveArticle($data)) {
                flash('article_message', 'Article publié avec succès', 'alert alert-success');
                redirect('articles/index');
            } else {
                flash('article_message', 'Erreur lors de la publication de l\'article', 'alert alert-danger');
                $this->render('create', $data);
            }
        } else {
            redirect('articles/create');
        }
    }

    public function show($id) {
        // Vérifie que l'ID est un entier
        if (!is_numeric($id)) {
            redirect('articles/index');
        }
    
        // Récupère l'article via le modèle
        $article = $this->articleModel->getArticleById($id);
    
        if (!$article) {
            flash('article_message', 'Article non trouvé', 'alert alert-danger');
            redirect('articles/index');
        }
    
        // Prépare les données pour la vue
        $data = [
            'title' => $article->title,
            'article' => $article,
        ];
    
        $this->render('show', $data);
    }

    // Méthode pour extraire les images depuis le contenu HTML
    private function extractImages($contents) {
        $images = [];
        foreach ($contents as $content) {
            preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);
            if (!empty($matches[1])) {
                $images = array_merge($images, $matches[1]);
            }
        }
        return $images;
    }
}
