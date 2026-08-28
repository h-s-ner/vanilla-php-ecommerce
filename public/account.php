<?php
require_once '../app/templates/header.php';
require_once '../app/controller/UserController.php';
require_once '../app/service/FormService.php';
require_once '../app/service/UserService.php';
require_once '../app/model/AuthModel.php';
require_once '../app/model/UserModel.php';


Middleware::guardAndRedirect(Middleware::isGuest());
$userModel =  new UserModel();
$authModel = new AuthModel();
/*try
{
  $userModel =  new UserModel();
  $authModel = new AuthModel();
}
catch (Throwable $e)
{
  http_response_code(500);
  echo "Datenbankverbindung fehlgeschlagen";
  exit;
}*/
$userController =  new UserController(
  new UserService(
    new FormService(),
    $authModel,
    $userModel
    )
  );

$user =  $userController->getUserById($_SESSION['user']['id']);
// check for both addresses
$invoiceAddresses = $userController->getAddresses($_SESSION['user']['id'],'2');
$deliveryAddresses = $userController->getAddresses($_SESSION['user']['id'],'1');

$errors = array();
// change password

if (isset($_POST['change_password']))
{
  $changePwSucceed = $userController->changePassword($_POST);
  if (is_array($changePwSucceed)){
    $errors = $changePwSucceed;
  }
  else{
    header('Location: account.php');
  }
}
//  update userData
if (isset($_POST['saveDetails'])){
  $updateSucceed = $userController->updateUserDetails($_POST);
  if (is_array($updateSucceed)){
    $errors = $updateSucceed ;
  }
  else{
    header('Location: account.php');
  }
}
// create new address
if (isset($_POST['create_address'])) {
  $result = $userController->createAddress($_POST);
  if ($result !== true){
    $errors = $result;
  }
}
require __DIR__.'/../app/templates/form_errors.php';
?>
<div class="container px-1">
  <div class="row justify-content-between flex-column flex-md-row">
    <div class="col-12  col-lg-5 p-3 mt-4 border border-success rounded">
     <h2>Change Account Settings</h2>
     <form action="" method="POST">
       <div class="mb-3">
        <label for="updatefname" class="form-label">First Name:</label>
        <input type="text" class="form-control" id="updatefname" name="updatefname"
          value="<?php echo htmlspecialchars($_SESSION['user']['fname'] ?? '');?>"
        >
       </div>
       <div class="mb-3">
        <label for="updatelname" class="form-label">Last Name:</label>
        <input type="text" class="form-control" id="updatelname" name="updatelname"
          value="<?php echo htmlspecialchars($_SESSION['user']['lname'] ?? '');?>"
        >
       </div>
       <div class="mb-3">
        <label for="updatephone" class="form-label">Phone:</label>
        <input type="text" class="form-control" id="updatephone" name="updatephone" value="<?php echo htmlspecialchars($_SESSION['user']['phone'] ?? '');?>">
       </div>
       <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
       <button type="submit" class="btn btn-danger" name="saveDetails">Save Details</button>
      </form>
    </div>

            <!-- for change password -->
    <div class="col-12 col-lg-5 p-3 mt-4 border border-success rounded">
      <h2>Change Password</h2>
      <form action="" method="POST">
        <div class="mb-3">
          <label for="currentPassword" class="form-label">Current Password:</label>
          <input type="password" class="form-control" id="currentPassword" name="current_password" >
        </div>
        <div class="mb-3">
          <label for="newPassword" class="form-label">New Password:</label>
          <input type="password" class="form-control" id="newPassword" name="new_password">
        </div>
        <div class="mb-3">
          <label for="confirmPassword" class="form-label">Confirm new Password:</label>
          <input type="password" class="form-control" id="confirmPassword" name="confirm_password">
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <button type="submit" class="btn btn-danger" name="change_password">Change</button>
      </form>
    </div>

    <div class="col-12 col-lg-5 p-3 mt-4 mb-2 border border-success rounded">
      <h2>Create Address</h2>
      <form class="col" method="post">

        <label for="type">Address Type</label>
        <select class="form-select" id="type" name="type">
            <option value="1">Delivery</option>
            <option value="2">Invoice</option>
        </select>
        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" class="form-control" id="city" name="city">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address">
        </div>
        <div class="mb-3">
            <label for="zip" class="form-label">Zip</label>
            <input type="text" class="form-control" id="zip" name="zip">
        </div>
       <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <button type="submit" class="btn btn-danger border border-success" name="create_address">Create</button>
      </form>
    </div>
  </div>
  <div class="row mt-5">
        <div class="">
          <h2 class="text-center">Invoice Addresses</h2>
          <div class="d-flex flex-wrap justify-content-center gap-2">
            <?php foreach ($invoiceAddresses as $address): ?>
                <div class="card mb-2 border-success" style="width: 18rem;">
                    <div class="card-body ">
                        <h5 class="card-title"><?php echo htmlspecialchars($address['city']) ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($address['address']) ?></p>
                        <p class="card-text"><?php echo htmlspecialchars($address['zip']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
          </div>
        </div>

       <div class="">
          <h2 class="text-center">Delivery Addresses</h2>
          <div class="d-flex flex-wrap justify-content-center gap-2">
            <?php foreach ($deliveryAddresses as $address): ?>
                <div class="card mb-2 border-success" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($address['city']) ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($address['address']) ?></p>
                        <p class="card-text"><?php echo htmlspecialchars($address['zip']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
          </div>
        </div>
    </div>
</div>


<?php  require_once '../app/templates/footer.php';?>