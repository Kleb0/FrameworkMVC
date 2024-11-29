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

    // Récupérer un article spécifique à vérifier
    public function getArticleToCurateById($id) {
        $this->db->query('SELECT * FROM articles_to_be_curated WHERE article_id = :id AND status = "pending"');
        $this->db->bind(':id', $id);
        return $this->db->findOne();
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

