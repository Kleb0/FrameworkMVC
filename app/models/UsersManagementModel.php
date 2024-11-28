<?php
class UsersManagementModel {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    //récupère un utilisateur par son id
    public function getUserById($id){
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->findOne();
    }

    //récupère tous les utilisateurs
    public function getAllusers(){
        $this->db->query("SELECT * FROM users");
        return $this->db->findAll();
    }

    //Met à jour le rôle d'un utilisateur
    public function updateUserRole($userId, $roleId, $roleName){
        $this->db->query("UPDATE users SET role = :role, role_name = :role_name WHERE id = :id");
        $this->db->bind(':role', $roleId);
        $this->db->bind(':role_name', $roleName);
        $this->db->bind(':id', $userId);

        return $this->db->execute();
    }

    //Supprime un utilisateur
    public function deleteUser($userId){
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $userId);

        return $this->db->execute();
    }
}