<?php
require_once '../app/templates/header.php';
require_once '../app/controller/AdminUserController.php';
require_once '../app/service/AdminUserService.php';
require_once '../app/model/UserModel.php';

Middleware::guardAndRedirect(!Middleware::isAdmin());

$adminUserController = new AdminUserController(
    new AdminUserService(
        new UserModel()
        )
    );
$users =  $adminUserController->getAllUsers();

if (empty($users))
{
    $errors =['There are no Users'];
    require __DIR__.'/../app/templates/form_errors.php';
    exit();
}

if (isset($_POST['updateStatus'])){
    $adminUserController->updateUserStatus($_POST['id'], $_POST['updateStatus']);
    header('Location: /admin_users.php');
    exit();
}

?>
<div class="my-2">
    <a href="admin_products.php" class="btn btn-primary hs-bg">Products</a>
    <a href="admin_orders.php" class="btn btn-success hs-bg">Orders</a>
    <a href="admin_create_product.php" class="btn btn-primary hs-bg">Create Product</a>
</div>
<h1>Users</h1>
<table class="table">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Firstname</th>
        <th scope="col">Lastname</th>
        <th scope="col">Email</th>
        <th scope="col">Status</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']) ?></td>
            <td><?php echo htmlspecialchars($user['fname']) ?></td>
            <td><?php echo htmlspecialchars($user['lname']) ?></td>
            <td><?php echo htmlspecialchars($user['email']) ?></td>
            <td><?php echo $user['active'] === 1 ? '<span class="text-success">Active</span>' : '<span class="text-danger">Not active</span>'; ?></td>
            <td>
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?php echo $user['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']);?>">
                    <button class="btn btn-<?php echo $user['active'] === 1 ? "danger":"primary"; ?>"
                     name="updateStatus" type="submit" value="<?php echo $user['active'] === 1 ? 0:1; ?>">
                    <?php echo $user['active'] === 1 ? "Deactivate":"Activate"; ?>
                    </button>
                </form>
            </td>

        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../app/templates/footer.php'; ?>