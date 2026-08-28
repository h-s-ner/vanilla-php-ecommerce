<?php
require_once '../app/templates/header.php';
require_once '../app/model/OrderModel.php';
require_once '../app/service/OrderService.php';
require_once '../app/controller/OrderController.php';

// if not User
Middleware::guardAndRedirect(Middleware::isGuest());
?>

<?php
$orderController = new OrderController(
    new OrderService(
        new OrderModel()
    )
);
$orders = $orderController->getOrdersByUserId($_SESSION['user']['id']);
?>
<?php if (count($orders) == 0): ?>
    <div class="alert alert-info" role="alert">
        Ops! you dont have any orders yet!
        <br><br>
        <a href="index.php" class="btn btn-primary">Shop Now</a>
    </div>
<?php endif; ?>

<?php if (count($orders) > 0): ?>
    <table class="table border border-success ">
        <thead>
        <tr>
            <th scope="col">Order Date</th>
            <th scope="col">Status</th>
            <th scope="col">Summe</th>
            <th scope="col">Anzahl Produkte</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
           <?php $status = htmlspecialchars($order['status']);
                 $status = $status == 1 ? 'Pending' : ($status == 2 ? 'Done':'Cancel');
            ?>
            <tr>

                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                <td><?php echo $status; ?></td>
                <td><?php echo htmlspecialchars($order['total_price']); ?> €</td>
                <td><?php echo htmlspecialchars($order['count_products']); ?></td>
                <td>
                    <a href="order_products.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="btn btn-success">Show</a>
                    <?php if ($order['status'] == 'Done'): ?>
                        <a href="download_invoice.php?file=<?php echo $order['invoice_pdf'] ?>" class="btn btn-danger">Download Invoice</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php require_once '../app/templates/footer.php'; ?>