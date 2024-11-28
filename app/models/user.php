<?php
class User {
    private $db;
    public function __construct(){
        $this->db = new Database;
    }
    public function findUserByEmail($email){
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->checkIfExist();
    }
    public function register($data){
        $this->db->query('INSERT INTO users (nom, email, password, role, role_name) VALUES (:nom, :email, :password, :role, :role_name)');
        $this->db->bind(':nom', $data['nom']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':role_name', $data['role_name']);
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function findUser($email){
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->findOne();
    }

    public function getAllUsers() {
        $this->db->query("SELECT * FROM users"); 
        return $this->db->findAll();
    }

    public function testDbConnection() {
        try {
            $this->db->query("SELECT 1");
            echo "La connexion à la base de données fonctionne.";
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
    

}