<?php

class ArticlesCurationModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Récupérer tous les articles en attente de validation
    public function getArticlesToCurate() {
        $this->db->query('SELECT * FROM articles_to_be_curated WHERE status = "pending" ORDER BY submission_date DESC');
        return $this->db->findAll();
    }

    public function getArticleToCurateById($id) {
        // Récupérer l'article à partir de la table 'articles_to_be_curated' avec l'ID de l'article
        $this->db->query('SELECT * FROM articles_to_be_curated WHERE article_id = :id AND status = "pending"');
        $this->db->bind(':id', $id);
        $article = $this->db->findOne();
    
        if ($article) {
            // Décoder les champs JSON si présents
            $article->paragraph_titles = isset($article->paragraph_titles) ? json_decode($article->paragraph_titles, true) : [];
            $article->paragraphs = isset($article->paragraphs) ? json_decode($article->paragraphs, true) : [];
            $article->paragraph_images = isset($article->paragraph_images) ? json_decode($article->paragraph_images, true) : [];
            $article->content = isset($article->content) ? $article->content : ''; // Assurer que 'content' existe
    
            return $article;
        }
    
        return null; // Si aucun article trouvé, retourner null
    }
    

    public function getArticleFromTableArticlesToPass($id) {
        // Effectue une requête pour récupérer l'article de la table 'articles' avec l'ID spécifié
        $this->db->query('SELECT * FROM articles WHERE id = :id');
        $this->db->bind(':id', $id);
        
        // Exécute la requête et récupère l'article
        $article = $this->db->findOne();
        
        // Retourne l'article récupéré, ou null si aucun article n'est trouvé
        return $article;
    }
    
    



    // Mettre à jour le statut d'un article
    public function updateCurationStatus($articleId, $status, $adminComments = '') {
        $this->db->query('UPDATE articles_to_be_curated SET status = :status, admin_comments = :admin_comments WHERE article_id = :article_id');
        $this->db->bind(':status', $status);
        $this->db->bind(':admin_comments', $adminComments);
        $this->db->bind(':article_id', $articleId);
        return $this->db->execute();
    }
}

