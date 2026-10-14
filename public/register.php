<?php
require_once '../app/templates/header.php';
require_once '../app/service/FormService.php';
require_once '../app/model/AuthModel.php';
require_once '../app/model/UserModel.php';

Middleware::guardAndRedirect(Middleware::isUser());
?>

<?php

$errors = array();
if (isset($_POST['register'])){
  echo('hie');
  $auth =  new AuthController(
    new AuthService(
      new FormService(),
      new AuthModel(),
      new UserModel()
      )
    );
  $result = $auth->register($_POST);
  if ($result === true){
    header('Location: login.php');
    exit;
  }
  else {
    $errors = $result;
  }
}
require __DIR__.'/../app/templates/form_errors.php';
?>
<div class="container">
  <div class="row">
    <div class="col col-lg-6 col-sm-12 offset-lg-3 ">
      <form action="" method="POST">
        <div class="container">
        <div class="mb-3">
          <label for="fname" class="form-label">First Name:</label>
          <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($_POST['fname'] ?? '');?>">
        </div>
        <div class="mb-3">
          <label for="lname" class="form-label">Last Name:</label>
          <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($_POST['lname'] ?? '');?>">
        </div>
        <div class="mb-3">
          <label for="phone" class="form-label">Phone:</label>
          <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? '');?>">
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email:</label>
          <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '');?>">
        </div>
        <div class="mb-3">
          <label for="Password" class="form-label">Password:</label>
          <input type="password" class="form-control" id="Password" name="password" value="<?php echo htmlspecialchars($_POST['password'] ?? '');?>">
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <button type="submit" class="btn btn-danger w-100" name="register">Register</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php  require_once '../app/templates/footer.php';?>