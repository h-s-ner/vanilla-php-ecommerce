<?php
Class AuthController{
    private array $errors = array();

    public function __construct(private readonly AuthService $authService)
    {

    }

    public function register(array $request):array|true{        
        return $this->authService->register($request);
    }
    public function login (array $request): array|true {
        return $this->authService->login($request);
    }
    public function logout(){
        $this->authService->logout();
    }
    
}
?>