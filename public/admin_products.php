<?php
require_once '../app/templates/header.php';
require_once '../app/controller/AdminProductController.php';
require_once '../app/service/AdminProductService.php';
require_once '../app/model/ProductModel.php';

Middleware::guardAndRedirect(!Middleware::isAdmin());

$adminProductController = new AdminProductController(
    new AdminProductService(
        formService:null,
        productModel: new ProductModel()
    )
);
$products = $adminProductController->index();

if (isset($_POST['delete'])){
    $adminProductController->destroy($_POST['id']);
    header('Location: /admin_products.php');
    exit;
}

?>

<div class="my-2">
    <a href="admin_users.php" class="btn btn-primary hs-bg">Users</a>
    <a href="admin_orders.php" class="btn btn-success hs-bg">Orders</a>
    <a href="admin_create_product.php" class="btn btn-primary hs-bg">Create Product</a>
</div>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Image</th>
        <th scope="col">Name</th>
        <th scope="col">Price</th>
        <th scope="col">Stock</th>
        <th scope="col">Status</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo htmlspecialchars($product['id']) ?></td>
            <td><img src="images/<?php echo $product['image'] ?: "default.png" ?>" alt=""
                     style="width: 100px; height: 100px; object-fit: cover"></td>
            <td><?php echo htmlspecialchars($product['name']) ?></td>
            <td><?php echo htmlspecialchars($product['price']) ?></td>
            <td><?php echo htmlspecialchars($product['stock']) ?></td>
            <td><?php echo $product['active'] ? '<span style="color: green">Active</span>' : '<span style="color: red">Not active</span>'; ?></td>
            <td>
                <a href="show_product.php?id=<?php echo htmlspecialchars($product['id']) ?>" class="btn btn-warning">Show</a>
                <a href="admin_edit_product.php?id=<?php echo htmlspecialchars($product['id'])?>" class="btn btn-primary">Edit</a>
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']) ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <button class="btn btn-danger" name="delete">Delete</button>
                </form>
            </td>

        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../app/templates/footer.php'; ?>
