<?php   
class AuthService{
    private array $errors = [];

    public function __construct(
        private readonly ?FormService $formService = null,
        private readonly ?AuthModel $authModel = null,
        private readonly ?UserModel $userModel = null
    ){}
    
    public function register($request)
    {
        Middleware::validateCsrfToken();
        Middleware::reGenerateCsrfToken();

        $fname = trim($request['fname'] ?? '');
        $lname = trim($request['lname'] ?? '');
        $phone = trim($request['phone'] ?? '');
        $email = trim($request['email'] ?? '');
        $password = $request['password'] ?? '';

        $fields = [
            'First name' => $fname,
            'Last name' =>$lname,
            'Phone Number' => $phone
        ];

        $this->errors = $this->formService->validateRequired($fields);
        $this->errors = array_merge(
            $this->errors,
            $this->formService->validateEmail($email,$this->authModel)
        );
        $this->errors = array_merge(
            $this->errors,
            $this->formService->validatePassword($password)
        );

        if (!empty($this->errors)){
            return $this->errors;
        }
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->authModel->register($fname, $lname, $phone, $email, $hashPassword);
        return true;
    }
    public function login($request)
    {
        Middleware::validateCsrfToken();
        Middleware::reGenerateCsrfToken();
        
        $email = $request['email'];
        $password = $request['password'];

        $this->errors = $this->formService->validateRequired(['Password'=>$password]);
        $this->errors = array_merge(
            $this->errors,
            $this->formService->validateEmail($email)
        );

        if (!empty($this->errors)){
            return $this->errors;
        }
        
        $user= $this->authModel->findUserByEmail($email);
        if (!$user){
            $this->errors[] = "Invalid credentials";
            return  $this->errors;
        }
        if ((int)$user['active'] !== 1){
            $this->errors[] = "Please activate your account";
            return $this->errors;
        }
        if (!password_verify($password, $user['password'])){
            $this->errors[] = "Invalid credentials";
            return $this->errors;
        }
        unset($user['password']);
        session_regenerate_id(true);
        $_SESSION['user'] = $user;
        return true;
    }
    public function logout(){
        Middleware::validateCsrfToken();
        session_destroy();
        header("Location: /");
        exit();
    }
}