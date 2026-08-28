<?php
class AdminUserController
{
    public function __construct(
        private readonly AdminUserService $adminUserService
    ){}

    public function updateUserStatus($id, $status)
    {
        $this->adminUserService->updateUserStatus($id, $status);
    }

    public function getAllUsers()
    {
        return $this->adminUserService->getAllUsers();
    }


}
?>