<?php
class Middleware{
    public static function isGuest():bool{
        return (!isset($_SESSION['user']));
    }
    public static function isUser():bool{
        return (isset($_SESSION['user']));        
    }
    public static function isAdmin():bool{
     return (self::isUser() && ($_SESSION['user']['role'] == 1));
    }

    // Direktes Guard für URL-Umleitungen
    public static function guardAndRedirect(bool $condition, string $redirectUrl = '/'): void {
        if ($condition){
            header("Location: $redirectUrl");
            exit();
        }
    }

    public static function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Token generieren
        }             
    }

    public static function reGenerateCsrfToken()
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Token regenerieren
    }

    // Methode zur Validierung des CSRF-Tokens
    public static function validateCsrfToken()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                self::reGenerateCsrfToken();
                die('Ungültiger CSRF-Token. Anfrage verweigert.');
            }
        }
    }

}
?>
