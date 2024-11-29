<?php
class ArticlesModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Sauvegarde un article
    public function saveArticle($data) {
        $this->db->query('INSERT INTO articles (user_id, title, paragraph_titles, paragraphs, content, images, publication_date) 
                           VALUES (:user_id, :title, :paragraph_titles, :paragraphs, :content, :images, :publication_date)');
        $this->db->bind(':user_id', $data['user_id']); 
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':paragraph_titles', json_encode($data['paragraph_titles']));
        $this->db->bind(':paragraphs', json_encode($data['paragraphs']));             
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':images', json_encode($data['images']));
        $this->db->bind(':publication_date', date('Y-m-d H:i:s')); 
    
        return $this->db->execute();
    }

    // Récupère tous les articles
    public function getAllArticles() {
        $this->db->query('SELECT * FROM articles ORDER BY publication_date DESC');
        return $this->db->findAll();
    }

    public function getArticleById($id) {
        $this->db->query('SELECT * FROM articles WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->findOne();
    }
    
}
