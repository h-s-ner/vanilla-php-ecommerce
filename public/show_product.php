<?php
require_once '../app/templates/header.php';
require_once '../app/controller/ProductController.php';
require_once '../app/controller/AdminProductController.php';
require_once '../app/service/ProductService.php';
require_once '../app/service/AdminProductService.php';
require_once '../app/model/ProductModel.php';
require_once '../app/core/Middleware.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}
if (Middleware::isAdmin())
{
    $productController = new AdminProductController(
        new AdminProductService(
            formService: null,
            productModel: new ProductModel()
        )
    );
}
else{
    $productController = new ProductController(
        new ProductService(
            productModel: new ProductModel()
        )
    );
    //var_dump($productController);
}


if (Middleware::isAdmin() && isset($_POST['delete'])) {
    Middleware::validateCsrfToken();
    Middleware::reGenerateCsrfToken();
    $productController->destroy($_GET['id']);
}

$product = $productController->show($_GET['id']);
if (!$product) {
    header('Location: /index.php');
    exit;
}

?>

<div class="row g-0 bg-body-secondary position-relative">
    <div class="col-md-6 mb-md-0 p-md-4 ">
        <div class="ratio ratio-16x9">
            <img src="images/<?php echo $product['image'] ?: "default.png" ?>" class="card-img-top"
                 style=" object-fit: cover">
        </div>
    </div>
    <div class="col-md-6 p-4 ps-md-0 d-flex flex-column justify-conten-between">
        <div>
            <h5 class="card-title mt-2"><?php echo htmlspecialchars($product['name']) ?></h5>
            <h6 class="card-title mt-2 ">Price: <?php echo htmlspecialchars($product['price']) ?>$</h6>
            <h6 class="card-title mt-2  ">Stock: <?php echo htmlspecialchars($product['stock']) ?> </h6>
            <hr>
            <p class="card-text"><?php echo htmlspecialchars($product['description']) ?></p>
        </div>
        <?php if (Middleware::isAdmin()): ?>
        <div classe="d-flex flex-row-reverse">

            <form action="" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']);?>">
                <button type="submit" class="btn btn-danger me-3" name="delete">Delete</button>
            </form>
            <a href="/admin_edit_product.php?id=<?php echo $product['id'] ?>" class="btn btn-primary me-3" >Edit</a>

        </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once '../app/templates/footer.php' ?>
