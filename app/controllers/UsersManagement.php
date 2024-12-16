<?php

class UsersManagement extends AbstractController {
    private $usersManagementModel;

    public function __construct() {
        $this->usersManagementModel = $this->model('UsersManagementModel'); // Charger le modèle User
    }

    public function index() {

        $users = $this->usersManagementModel->getAllUsers();

        
        if (!$users) 
        {
            die("Erreur : Aucun utilisateur trouvé ou problème de récupération des données.");
        }

        $data = [
            'title' => 'Gestion des utilisateurs',
            'users' => $users // Transmettre les utilisateurs à la vue
        ];

        $this->render('index', $data);
    }

    // //(j'ai probablement fait de la merde ici)
    // public function updateRole($id){
    //     if($_SERVER['REQUEST_METHOD'] === 'POST') 
    //     {
    //         $newRoleId = $_POST['role_id'];
    //         $newRoleName = $newRoleId == 1 ? 'utilisateur' : 'administrateur';

    //         if ($this->usersManagementModel->updateUserRole($id, $newRoleId, $newRoleName))
    //         {
    //             flash('roleUpdated', 'Rôle mis à jour avec succès', 'alert alert-success');   
    //         }
    //         else
    //         {
    //             flash('roleUpdated', 'Erreur lors de la mise à jour du rôle', 'alert alert-danger');
    //         }

    //         redirect('usersManagement/index');

    //     }
    // }

    public function editRole($id) {
        $user = $this->usersManagementModel->getUserById($id);

        if(!$user)
        {
            flash('user_message', 'Utilisateur non trouvé', 'alert alert-danger');
            redirect('usersManagement/index');
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            $newRoleId = $_POST['role_id'];
            $newRoleName = $newRoleId == 1 ? 'utilisateur' : 'administrateur';

            if($this->usersManagementModel->updateUserRole($id, $newRoleId, $newRoleName))
            {
                flash('user_message', 'Rôle mis à jour avec succès', 'alert alert-success');
            }
            else
            {
                flash('user_message', 'Erreur lors de la mise à jour du rôle', 'alert alert-danger');
            }
            
            redirect('usersManagement/index');
        }
        else
        {
            $data = [
                'title'=> 'Modifier le rôle de l\'utilisateur',
                'user'=> $user
            ];

            $this->render('editRole', $data);
        }
    }




    public function delete($id){
        if($this->usersManagementModel->deleteUser($id))
        {
            flash('user_message', 'Utilisateur supprimé avec succès', 'alert alert-success');
        }
        else
        {
            flash('user_message', 'Erreur lors de la suppression de l\'utilisateur', 'alert alert-danger');
        }
        redirect('usersManagement/index');
    }
}
