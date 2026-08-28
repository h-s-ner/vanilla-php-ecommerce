<?php
$cookieParams = [
    'lifetime' => 0,                  // Cookie bis Browser geschlossen
    'path' => '/',                     // Gültig für die ganze Website
    'domain' => '',                    // leer = aktueller Host, gut für localhost
    'secure' => isset($_SERVER['HTTPS']), // nur HTTPS, passt automatisch
    'httponly' => true,                // kein Zugriff per JavaScript
    'samesite' => 'Strict'             // schützt vor CSRF
];
session_set_cookie_params($cookieParams);
session_start();
require_once '../app/core/Middleware.php';
require_once '../app/core/ErrorHandler.php';
require_once '../app/controller/AuthController.php';
require_once '../app/service/AuthService.php';
set_exception_handler([ErrorHandler::class, 'handle']);
Middleware::generateCsrfToken();

// LOGOUT
if (isset($_POST['logout'])) {
    $authController = new AuthController(new AuthService());
    $authController->logout();
}
//echo $_SESSION['csrf_token'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
     rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
     crossorigin="anonymous">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

     <link rel="stylesheet" href="/../css/bootstrap-override.css">
     <link rel="stylesheet" href="/../css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-danger py-3">
  <div class="container-fluid px-5 ">
    <a class="navbar-brand" href="/">Ner Shop</a>
    <button class="navbar-toggler" type="button"
    data-bs-toggle="collapse"
    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav me-2">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="products.php">Products</a>
        </li>
        <?php if (Middleware::isGuest()): ?>
        <li class="nav-item">
          <a class="nav-link active" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="register.php">Register</a>
        </li>
        <?php endif; ?>

        <?php if (Middleware::isAdmin()): ?>
        <li class="nav-item">
          <a class="nav-link active" href="admin.php">Admin</a>
        </li>
        <?php endif;?>

        <li class="nav-item">
          <a class="nav-link active" href="/cart.php">Cart</a>
        </li>
        <?php if (Middleware::isUser()): ?>
        <li class="nav-item">
          <a class="nav-link active" href="/account.php">Account</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="/orders.php">Orders</a>
        </li>
        <li class="nav-item">
          <form action="" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <button class="nav-link active" name="logout">Logout</button>
          </form>
        </li>
        <?php endif;?>
      </ul>
      <form class="d-flex" role="search" action="/products.php">
        <input class="form-control me-2" type="search" placeholder="Search" name="search" aria-label="Search"/>
        <button class="text-white btn  btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
<div class="p-5"> <!---- content --->

