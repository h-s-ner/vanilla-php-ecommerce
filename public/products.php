<?php
require_once '../app/templates/header.php';
require_once '../app/controller/ProductController.php';
require_once '../app/service/ProductService.php';
require_once '../app/model/ProductModel.php';
require_once '../app/controller/CartController.php';
require_once '../app/service/CartService.php';
$productModel = new ProductModel();

/*try{
  $productModel = new ProductModel();

}
catch (Throwable $e){

    error_log($e->getMessage());
    http_response_code(500);
    echo "Server Internal Error";
    exit;
}*/
if (isset($_POST['add_to_cart'])) {
    $cartController = new CartController(
        new CartService(
            new ProductModel()
        )
    );
    $success= $cartController->store($_POST);
    if (!$success) {
        header('Location: /index.php');
        exit();
    }
    header('Location: /cart.php');
    exit();
}

$productController = new ProductController(
    new ProductService(
        new ProductModel()
    )
);

if(isset($_GET['all'])&& $_GET['all']==true)
{
 $products = $productController->index();
}
else if(isset($_GET['search']))
{
    $products = $productController->search($_GET['search']);
}
else
{

    $page = (isset($_GET['page']) && intval($_GET['page']) > 1) ? intval($_GET['page']) : 1;

    $data= $productController->getPageProducts($page);
    $products = $data['products'];
    $pageCount = $data['pageCount'];
}
/*try
{
    $products = $homeController->index();
}
catch(Throwable $exception){
    error_log($exception->getMessage() . "\n" . $exception->getTraceAsString());
    echo "Internal Server Error";
    exit();
}*/
?>
<?php
if(isset($page,$pageCount)){
    require '../app/templates/pagination.php';
}
?>
<div class="container mt-4">
    <div class="row">
        <?php foreach($products as $product):?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mt-4">
                <div class="card h-100">
                    <div class="">
                        <a href="show_product.php?id=<?php echo htmlspecialchars($product['id']) ?>" class="w-100" title="Show">
                            <img src="images/<?php echo $product['image']?: "default.png"?>"
                                class="card-img-top cover"
                                alt="<?php echo $product['name'];?>"
                            >
                        </a>
                    </div>
                    <div class="card-body">
                        <p class="card-title fw-bold text-truncate" title="<?php echo $product['name'];?>"><?php echo $product['name'];?> </p>
                        <p class="card-title">Price: <?php echo htmlspecialchars($product['price']) ?>$</p>
                        <p class="card-title">Stock: <?php echo htmlspecialchars($product['stock'])?></p>
                        <?php if(!Middleware::isAdmin()): ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <select class="form-select my-1" aria-label="Stock" name="quantity">
                            <?php for($x = 1; $x <= 30 && $x <= $product['stock']; $x++): ?>
                            <option value="<?php echo $x; ?>">Quantity: <?php echo $x; ?></option>
                            <?php endfor; ?>
                        </select>
                        <input type="hidden" name="product_id" value="<?php echo $product['id']?>">
                        <br>

                        <Button class="btn btn-danger w-100" type="submit" name="add_to_cart">In Warenkorb</button>
                        </form>
                        <?php else: ?>
                        <a href="admin_edit_product.php?id=<?php echo htmlspecialchars($product['id'])?>" class="btn btn-primary mb-1 w-100">Edit</a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>
<?php

    require '../app/templates/pagination.php';

?>
<?php  require_once '../app/templates/footer.php';?>