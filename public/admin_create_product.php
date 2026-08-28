<?php
require_once '../app/templates/header.php';
require_once '../app/controller/AdminProductController.php';
require_once '../app/service/AdminProductService.php';
require_once '../app/service/FormService.php';
require_once '../app/model/ProductModel.php';

Middleware::guardAndRedirect(!Middleware::isAdmin());
$productModel = new ProductModel();
/*try
{
  $productModel = new ProductModel();
}
catch (Throwable $e)
{
    error_log($e->getMessage());
    http_response_code(500);
    echo "Server Internal Error";
    exit;
}*/
$adminProductController = new AdminProductController(
    new AdminProductService(
        new FormService(),
        $productModel // dependency injection
    )
);

$errors = [];

if (isset($_POST['createProduct'])){
    $result = $adminProductController->store($_POST, $_FILES);
    if ($result === true){
        header('Location: admin_products.php');
        exit;
    }else {
        $errors = $result;
    }
}
?>

<?php require __DIR__.'/../app/templates/form_errors.php'; ?>


<div class="my-2">
    <a href="admin_products.php" class="btn btn-primary hs-bg">Products</a>
    <a href="admin_users.php" class="btn btn-primary hs-bg">Users</a>
    <a href="admin_orders.php" class="btn btn-success hs-bg">Orders</a>
</div>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <input type="text" class="form-control" id="description" name="description">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="text" class="form-control" id="price" name="price">
    </div>
    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="text" class="form-control" id="stock" name="stock">
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="active" name="active" value="active" checked>
        <label class="form-check-label" for="active">Active</label>
    </div>
    <div class="mb-3">
        <input type="file" name="product_image" id="image">
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    <button type="submit" class="btn btn-primary" name="createProduct">Create</button>
</form>

<?php require_once '../app/templates/footer.php';?>
