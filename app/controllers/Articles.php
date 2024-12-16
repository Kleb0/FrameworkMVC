<?php

define('UPLOAD_PATH', '/path/to/upload'); // Define the constant UPLOAD_PATH
define('UPLOAD_URL', '/url/to/upload'); // Define the constant UPLOAD_URL

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
        // error_log(print_r($_FILES['paragraph_images'], true));
        if (!isset($_SESSION['user_id']))
         {
            flash('article_message', 'Vous devez être connecté pour publier un article', 'alert alert-danger');
            redirect('users/login');
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
    
            $totalArticles = $this->articleModel->countArticles();
            $nextArticleId = $totalArticles + 1;
    
            // Gestion des uploads d'images
            $paragraphImages = [];
            if (!empty($_FILES['paragraph_images']))
            {
                foreach ($_FILES['paragraph_images']['name'] as $index => $files)
                {
                    $paragraphImages[$index] = $this->handleUploadedImages($_FILES['paragraph_images'], $index);
                }
            }
    
            // Préparation des données
            $data = [
                'id' => $nextArticleId,
                'user_id' => $_SESSION['user_id'],
                'title' => htmlspecialchars($_POST['title']),
                'paragraph_titles' => $_POST['paragraph_titles'],
                'paragraphs' => $_POST['paragraphs'],
                'paragraph_images' => $paragraphImages, // Images des paragraphes
                'content' => htmlspecialchars($_POST['content']),
                'images' => $this->extractImages(array_merge(
                    [$_POST['content']],
                    $_POST['paragraphs']
                )),
            ];
    
            // Enregistrement en base
            if ($this->articleModel->saveArticle($data)) 
            {
                // Transfert pour curation
                $this->articleModel->transferToCuration($nextArticleId, $_SESSION['user_id']);
    
                flash('article_message', 'Article Envoyé', 'alert alert-success');
                redirect('articles/index');
            } 
            else
            {
                flash('article_message', 'Erreur lors de la publication de l\'article', 'alert alert-danger');
                $this->render('create', $data);
            }
        } 
        else {
            redirect('articles/create');
        }
    }

    private function handleUploadedImages($files, $index) {
    $uploadedImages = [];

    if (isset($files['tmp_name'][$index]))
    {
        foreach ($files['tmp_name'][$index] as $key => $tmpName) 
        {
            if ($files['error'][$index][$key] === UPLOAD_ERR_OK) 
            {
                // Générer un nom unique pour chaque fichier
                $filename = uniqid() . '_' . basename($files['name'][$index][$key]);
                $destination = UPLOAD_PATH . '/' . $filename;

                // Déplacer le fichier vers le dossier de destination
                if (move_uploaded_file($tmpName, $destination))
                {
                    // Ajouter l'URL relative de l'image dans la liste
                    $uploadedImages[] = UPLOAD_URL . '/' . $filename;
                }
            }
        }
    }

    return $uploadedImages;
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
