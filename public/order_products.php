<?php require_once '../app/templates/header.php';
require_once '../app/model/OrderModel.php';
require_once '../app/service/OrderService.php';
require_once '../app/controller/OrderController.php';


// MIDDLEWARE. RETURN THE GUEST TO INDEX PHP
Middleware::guardAndRedirect(Middleware::isGuest());
?>

<?php
$orderController = new OrderController(
    new OrderService(
        new OrderModel()
    )
);


// Check if id is set
if (!isset($_GET['id'])) {
    header('location: index.php');
    exit();
}
$order_id = intval($_GET['id']);

$orderData = $orderController->getOrderData($order_id);
$deliveryAddress = $orderData['deliveryAddress'];
$invoiceAddress = $orderData['invoiceAddress'];
$orderProducts = $orderData['orderProducts'];

?>

    <div class="row align-items-start mt-4">
        <div class="col">
            <h2>Invoice Address</h2>
            <div class="card mt-4 mb-4">
                <div class="card-header">
                    <?php echo htmlspecialchars($invoiceAddress['InvoiceAddress']); ?>
                </div>
            </div>
        </div>

        <div class="col">
            <h2>Delivery Address</h2>
            <div class="card mt-4 mb-4">
                <div class="card-header">
                    <?php echo htmlspecialchars($deliveryAddress['DeliveryAddress']); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table border border-success">
            <thead>
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">Quantity</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orderProducts as $product): ?>
                <tr>
                    <td><img width="100px" height="100px" src="./images/<?php echo htmlspecialchars($product['image'] ?: 'default.png'); ?>" alt=""></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['price']); ?> €</td>
                    <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php require_once '../app/templates/footer.php'; ?>