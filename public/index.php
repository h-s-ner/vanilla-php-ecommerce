<?php
require_once '../app/templates/header.php';
require_once '../app/controller/HomeController.php';
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
$homeController = new HomeController(
    new ProductService(
        new ProductModel()
    )
);
$productgroups = $homeController->index();
/*try
{
    $products = $homeController->index();
}
catch(Throwable $exception){
    error_log($exception->getMessage() . "\n" . $exception->getTraceAsString());
    echo "Internal Server Error";
    exit();
}*/
$i=0;
?>


<div id="newest-product-carousel" class="carousel slide">

  <div class="carousel-inner">
    <?php foreach($productgroups as $products):?>
    <div class="carousel-item <?php if($i==0) echo "active"; ?>">
        <div class="container mt-4">
    <div class="row">
        <?php foreach($products as $product):?>
        <div class="col-12 col-sm-6 col-md-3 mt-4">
            <div class="card h-100">
                <div class="">
                    <a href="show_product.php?id=<?php echo htmlspecialchars($product['id']) ?>" class="w-100" title="Show">
                        <img src="images/<?php echo $product['image']?: "default.png"?>"
                            class="card-img-top cover"
                            alt="<?php echo $product['description']?: $product['name']?>"
                        >
                    </a>
                </div>
                <div class="card-body">
                    <p class="card-title fw-bold text-truncate" title="<?php echo $product['name'];?>"><?php echo $product['name'];?> </p>
                    <p class="card-title mb-0">Price: <?php echo htmlspecialchars($product['price']) ?>$</p>
                    <p class="card-title">Stock: <?php echo htmlspecialchars($product['stock'])?></p>
                    <?php if(!Middleware::isAdmin()): ?>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <select class="form-select" aria-label="Stock" name="quantity">
                        <?php for($x = 1; $x <= 30 && $x <= $product['stock']; $x++): ?>
                        <option value="<?php echo $x; ?>">Quantity: <?php echo $x; ?></option>
                        <?php endfor; ?>
                    </select>
                    <input type="hidden" name="product_id" value="<?php echo $product['id']?>">
                    <br>

                    <Button class="btn btn-danger w-100" type="submit" name="add_to_cart">In Warenkorb</button>
                    </form>
                    <?php else: ?>
                    <a href="admin_edit_product.php?id=<?php echo htmlspecialchars($product['id'])?>" class="btn btn-primary mb-2 w-100">Edit</a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
        </div>
    </div>
    <?php $i++; ?>
    <?php endforeach; ?>
  </div>
  <button class="carousel-control-prev justify-content-start" type="button" data-bs-target="#newest-product-carousel" data-bs-slide="prev">
   <span class=" bi bi-arrow-left-circle text-danger fs-1" aria-hidden="true"> </span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next justify-content-end" type="button" data-bs-target="#newest-product-carousel" data-bs-slide="next">
    <span class=" bi bi-arrow-right-circle text-danger fs-1" aria-hidden="true"> </span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<?php  require_once '../app/templates/footer.php';?>