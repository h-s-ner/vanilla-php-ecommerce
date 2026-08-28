<?php
require_once '../app/templates/header.php';
require_once '../app/controller/ProductController.php';
require_once '../app/controller/CartController.php';
require_once '../app/service/CartService.php';
require_once '../app/model/ProductModel.php';
$productModel = new ProductModel();
/*try
{
  $productModel = new ProductModel();
}
catch (Throwable $e)
{
  http_response_code(500);
  echo $e->getMessage();
  exit;
}*/
$cartController = new CartController(
    new CartService(
       $productModel
    )
);

if (isset($_POST['delete'])){
    $cartController->destroy($_POST['id']);
}
if (isset($_POST['update'])){
    $cartController->update($_POST);
}
$products = $cartController->index();
$cart_total = 0;
 ?>
 <?php if ($products == null): ?>
    <div class="alert alert-info" role="alert">
        Ops! you dont have any products yet!
        <br><br>
        <a href="index.php" class="btn btn-primary">Shop Now</a>
    </div>
<?php endif; ?>
<?php if ($products): ?>
 <table class="table">
 <thead>
 <tr>
     <th scope="col">Image</th>
     <th scope="col">Name</th>
     <th scope="col">Price</th>
     <th scope="col">Quantity</th>
     <th scope="col">Subtotal</th>
     <th scope="col">Actions</th>
 </tr>
 </thead>
 <tbody>

 <?php foreach ($products as $product): ?>
        <tr>
            <td><img src="images/<?php echo $product['image'] ?: "default.png" ?>" alt=""
                     style="width: 100px; height: 100px; object-fit: cover"></td>
            <td><?php echo htmlspecialchars($product['name']) ?></td>
            <td><?php echo htmlspecialchars($product['price']) ?>$</td>
            <td>
                <form action="" method="POST">
                    <select class="form-select" aria-label="Stock" name="quantity">
                        <?php for ($x = 1; $x <= 30 && $x <= $product['stock']; $x++): ?>
                            <option value="<?php echo $x ?>" <?php echo $x == $product['quantity'] ? 'selected' : '' ?>>Quantity: <?php echo $x ?></option>
                        <?php endfor; ?>
                    </select>
                    <input type="hidden" name="product_id" value="<?php echo $product['id']?>">
                    <br>
                    <button class="btn btn-primary" name="update">Update Quantity</button>
                </form>
            </td>
            <td><?php echo htmlspecialchars($product['quantity']) * htmlspecialchars($product['price']) ?>$</td>
            <td>
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?php echo $product['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?>">
                    <button class="btn btn-danger" name="delete">Remove</button>
                </form>
            </td>
        </tr>
    <?php $cart_total += $product['quantity'] * $product['price'] ?>
    <?php endforeach; ?>
 </tbody>
</table>
<div>
<div>Cart Total: <?php echo $cart_total?>$</div>
    <br>
    <a href="/checkout.php" class="btn btn-success">Checkout</a>
    <a href="/" class="btn btn-primary">schopen</a>
</div>
<?php endif; ?>

<?php  require_once '../app/templates/footer.php';?>