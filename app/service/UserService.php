<?php

class UserService{

    private array $errors = [];

    public function __construct(
        private readonly ?FormService $formService = null,
        private readonly ?AuthModel $authModel = null,
        private readonly ?UserModel $userModel = null
    ){}

    public function getUserById(int $id){

        return $this->userModel->findById($id);
    }

    public function changePassword(array $request)
    {
        Middleware::validateCsrfToken();
        Middleware::reGenerateCsrfToken();
        $userId = $_SESSION['user']['id'];
        $current = $request['current_password'] ?? '';
        $new = $request['new_password'] ?? '';
        $confirm = $request['confirm_password'] ?? '';
        $this->errors = $this->formService->validateRequired([
            'Current Password' => $current,
            'New Password' => $new,
            'Confirmation' => $confirm]);
        if (!empty($this->errors)){
            return  $this->errors;
        }
        $this->errors = $this->formService->validatePasswordLength($new);
        if (!empty($this->errors)){
            return  $this->errors;
        }
        if ($new !== $confirm){
            return ["New password and confirmation do not match."];
        }
        // Aktuelles Passwort prüfen
        $hash = $this->authModel->getPasswordHash($userId);
        if (!password_verify($current, $hash)) {
            return ["Current password is incorrect."];
        }
        // new PasswordHash
        $newHash = password_hash($new, PASSWORD_DEFAULT);
        // update Password in db
        if ($this->authModel->updatePassword($userId, $newHash)) {
            return true;
        } else {
            $this->errors[] = "Failed to update password.";
        }
        return $this->errors;
    }

    public function updateUserDetails(array $request){
        Middleware::validateCsrfToken();
        Middleware::reGenerateCsrfToken();

        $fname =  $request['updatefname'];
        $lname =  $request['updatelname'];
        $phone =  $request['updatephone'];


        $this->errors = $this->formService->validateRequired([
            'First Name' => $fname,
            'Last Name'=>$lname,
            'Phone'=>$phone
        ]);
        /*if (empty($email)){
            $this->errors[] = "Email is required";
        }
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->errors[] = "Invalid Email";
        }*/
        // wenn user ein neu email eingibt, aber diese ist von einem  andere user
        /*** session ist falsch
        else if (is_array($this->authModel->findUserByEmail($email) && $email !== $_SESSION['user']['email'])){
            $this->errors[] = "Email already exists";
        }*/
        if (empty($this->errors)){
            $this->userModel->updateUserDetails($_SESSION['user']['id'], $fname, $lname, $phone);
            $_SESSION['user']['fname'] = $fname;
            $_SESSION['user']['lname'] = $lname;
            $_SESSION['user']['phone'] = $phone;
            return true;
        }
        return $this->errors;
    }

    public function createAddress(array $request)
    {
        Middleware::validateCsrfToken();
        Middleware::reGenerateCsrfToken();

        $type = $request['type'];
        $address = $request['address'];
        $city = $request['city'];
        $zip = $request['zip'];

        if (empty($type)) {
            $this->errors[] = "Please select a type";
        } else if ($type != 1 && $type != 2) {
            $this->errors[] = "Please select a valid type";
        }

        if (empty($address)) {
            $this->errors[] = "Please enter an address";
        }

        if (empty($city)) {
            $this->errors[] = "Please enter a city";
        }

        if (empty($zip)) {
            $this->errors[] = "Please enter a zip code";
        } else if (!is_numeric($zip)) {
            $this->errors[] = "Please enter a valid zip code";
        } else if (strlen($zip) != 4) {
            $this->errors[] = "Please enter a valid zip code";
        }

        if (empty($this->errors)) {
            $this->userModel->storeAddress($_SESSION['user']['id'], $type, $address, $city, $zip);
            header("Location: /account.php");
            return true;
        }
        return $this->errors;
    }
    public function getAddresses(int $user_id, int $type)
    {
        return $this->userModel->getAddresses($user_id, $type);
    }
}
?>