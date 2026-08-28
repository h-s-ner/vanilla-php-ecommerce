<?php

class UserController
{
    private array $errors = [];

    public function __construct(private readonly UserService $userService)
    {
            
    }
    public function getUserById($id){
        return $this->userService->getUserById($id);    
    }
        
    public function getAddresses($user_id, $type)
    {   
        return $this->userService->getAddresses($user_id, $type);
    }

    public function createAddress(array $request)
    {
        $this->userService->createAddress($request);
    }
    public function updateUserDetails(array $request)
    {
        return $this->userService->updateUserDetails($request);

    }
    public function changePassword(array $request)
    {
        return $this->userService->changePassword($request);
    }
}