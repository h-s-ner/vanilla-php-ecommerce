<?php
class AdminUserService{
   
    public function __construct(private readonly UserModel $userModel)
    {
       
    }
    public function updateUserStatus($id, $status){
        MiddleWare::validateCsrfToken();
        Middleware::reGenerateCsrfToken();
        $this->userModel->updateUserStatus($id, $status);
       
    }
    public function getAllUsers()
    {
        $users = $this->userModel->getAllUsers();
        if ($users){   
            return $users;
        }
        return false;      
    }
}