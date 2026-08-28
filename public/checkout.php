<?php
require_once '../app/templates/header.php';
require_once '../app/controller/CheckoutController.php';
require_once '../app/service/CheckoutService.php';
require_once '../app/service/CartService.php';
require_once '../app/controller/UserController.php';
require_once '../app/service/UserService.php';
require_once '../app/model/UserModel.php';
require_once '../app/controller/ProductController.php';
require_once '../app/model/ProductModel.php';
require_once '../app/model/OrderModel.php';

Middleware::guardAndRedirect(Middleware::isGuest(),'/login.php');

$checkoutService = new CheckoutService(
    new ProductModel(),
    new OrderModel()
);
$cartService = new CartService( new ProductModel());
$checkoutController =  new CheckoutController($checkoutService,$cartService);
if (isset($_POST['checkout'])) {
    $checkoutController->checkout($_POST);
}
$products = $checkoutController->index();

?>


<?php if ($products == null): ?>
    <div class="alert alert-info" role="alert">
        Ops! you dont have any products yet!
        <br><br>
        <a href="index.php" class="btn btn-primary">Shop Now</a>
    </div>

<?php endif; ?>

<?php

$cart_total = 0;
$userController =  new UserController(
    new UserService(
        formService: null,
        authModel: null,
        userModel: new UserModel()
    )
);
$user =  $userController->getUserById($_SESSION['user']['id']);

$invoice_addresses = $userController->getAddresses($_SESSION['user']['id'],'2');// type=2

$delivery_addresses = $userController->getAddresses($_SESSION['user']['id'],'1'); // type=1
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $total = 0;
    $productModel = new ProductModel();
    foreach ($_SESSION['cart'] as $key => $value) {
        $id = $key;
        $cart_products[$id] = $productModel->show($id);

        //REMOVE THE PRODUCT FROM THE CART IF IT DOES NOT EXIST ANYMORE IN THE DATABASE
        if (!$cart_products[$id]) {
            unset($cart_products[$id]);
            unset($_SESSION['cart'][$id]);
        } else {
            // If the user has e.g. 100 qty in the session but only 50 are available in the database,
            // only 50 qty will be saved in the cart.
            $cart_products[$id]['quantity'] = min($value['quantity'], $cart_products[$id]['stock']);
            // SAVE THE NEW STOCK IN THE SESSION
            $_SESSION['cart'][$key]['quantity'] = $cart_products[$id]['quantity'];

        }

    }


}
?>

<?php if ($products): ?>
 <table class="table">
 <thead>
 <tr>
     <th scope="col">Image</th>
     <th scope="col">Name</th>
     <th scope="col">Price</th>
     <th scope="col">Quantity</th>
     <th scope="col">Subtotal</th>
 </tr>
 </thead>
 <tbody>

 <?php foreach ($products as $product): ?>
        <tr>
            <td><img src="images/<?php echo $product['image'] ?: "default.png" ?>" alt=""
                     style="width: 100px; height: 100px; object-fit: cover"></td>
            <td><?php echo htmlspecialchars($product['name']) ?></td>
            <td><?php echo htmlspecialchars($product['price']) ?>$</td>
            <td><?php echo htmlspecialchars($product['quantity']) ?></td>
            <td><?php echo htmlspecialchars($product['quantity']) * htmlspecialchars($product['price']) ?>$</td>
        </tr>
    <?php $cart_total += $product['quantity'] * $product['price'] ?>
    <?php endforeach; ?>
 </tbody>
</table>
<div>
<div>Total: <?php echo $cart_total?>$</div>
    <br>
    <form action="" method="POST">
        <div class="row align-items-start">

            <div class="col">
                <h2>Invoice Addresses</h2>
                <?php foreach ($invoice_addresses as $address) : ?>

                    <label for="<?php echo htmlspecialchars($address['id']).'-invoice_address' ?>"
                           class="card mt-4 mb-4 card-body">
                        <blockquote class="blockquote mb-0">
                            <p><?php echo htmlspecialchars($address ['address']); ?>
                                - <?php echo htmlspecialchars($address ['zip']); ?>
                                - <?php echo htmlspecialchars($address ['city']); ?></p>
                            <input type="radio" name="invoice_address_id"
                                   id="<?php echo htmlspecialchars($address['id']).'-invoice_address' ?>"
                                   value="<?php echo htmlspecialchars($address['id']) ?>">
                        </blockquote>

                    </label>

                <?php endforeach; ?>
            </div>
            <div class="col">
                <h2>Delivery address</h2>

                <?php foreach ($delivery_addresses as $address) : ?>

                    <label for="<?php echo htmlspecialchars($address['id']).'-address' ?>"
                           class="card mt-4 mb-4 card-body">


                        <blockquote class="blockquote mb-0">
                            <p><?php echo htmlspecialchars($address ['address']); ?>
                                - <?php echo htmlspecialchars($address ['zip']); ?>
                                - <?php echo htmlspecialchars($address ['city']); ?></p>
                            <input type="radio" name="delivery_address_id"
                                   id="<?php echo htmlspecialchars($address['id']).'-address' ?>"
                                   value="<?php echo htmlspecialchars($address['id']) ?>">
                        </blockquote>

                    </label>


                <?php endforeach; ?>
            </div>

        </div>
       <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <button type="submit" class="btn btn-success" name="checkout">Buy Now</button>
    </form>
</div>
<?php endif; ?>
<?php require_once '../app/templates/footer.php'; ?>