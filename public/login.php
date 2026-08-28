<?php
require_once '../app/templates/header.php';
require_once '../app/service/FormService.php';
require_once '../app/model/AuthModel.php';
require_once '../app/model/UserModel.php';

Middleware::guardAndRedirect(Middleware::isUser());
?>

<?php
$errors = array();

if (isset($_POST['login'])){
  $userModel =  new UserModel();
  $authModel = new AuthModel();
  /*try{

  }
  catch (Throwable $e)
  {

    http_response_code(500);
    echo "Server Internal Error";
    exit;
  }*/
  $auth =  new AuthController(
    new AuthService(
      new FormService(),
      $authModel,
      $userModel
    )
  );
  $result = $auth->login($_POST);
  if ($result === true){
    header('Location: index.php');
    exit;
  }
  else {
    $errors = $result;
  }
}
 require __DIR__.'/../app/templates/form_errors.php';
?>

<div class="container mt-2">
  <div class="row">
    <div class="col col-lg-6 col-sm-12 offset-lg-3 ">
      <form action="" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Email:</label>
          <input  type="text" class="form-control" id="email"
            name="email"
            value="<?php echo htmlspecialchars($_POST['email'] ?? '');?>"
          >
        </div>
        <div class="mb-3">
          <label for="Password" class="form-label">Password:</label>
          <input  type="password" class="form-control"
                  id="Password" name="password"
                  value="<?php echo htmlspecialchars($_POST['password'] ?? '');?>"
          >
        </div>
        <input type="hidden" name="csrf_token"
          value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>"
        >
        <button type="submit" class="btn btn-danger w-100" name="login">Login</button>
      </form>
    </div>
  </div>
</div>
<?php  require_once '../app/templates/footer.php';?>