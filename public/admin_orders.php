<?php

require_once '../app/templates/header.php';
require_once '../app/controller/AdminOrderController.php';
require_once '../app/service/AdminOrderService.php';
require_once '../app/model/OrderModel.php';
require_once '../app/enum/OrderStatus.php';

Middleware::guardAndRedirect(!Middleware::isAdmin());
?>

<?php
$orderController = new AdminOrderController(
    new AdminOrderService(
        new OrderModel()
    )
    );


if (isset($_POST['order_done'])) {

    $order_id = $_POST['order_id'];

    //Update status (change the status id to done )
    $orderController->updateStatus($order_id, OrderStatus::DONE);

    header('location: /admin_orders.php');
    exit;
}


if (isset($_POST['order_cancel'])) {

    $order_id = $_POST['order_id'];

    //Update status (change the status id to cancel)
    $orderController->updateStatus($order_id , OrderStatus::CANCELED);

    header('location: /admin_orders.php');
    exit;
}
$orders = $orderController->index();
?>
<div class="my-2">
    <a href="admin_products.php" class="btn btn-primary hs-bg">Products</a>
    <a href="admin_users.php" class="btn btn-primary hs-bg">Users</a>
</div>
<table class="table border border-success">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Order Date</th>
            <th scope="col">Status</th>
            <th scope="col">Summe</th>
            <th scope="col">Anzahl Produkte</th>
            <th>Actions</th>
            <th>Status Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <th scope="row"><?php echo htmlspecialchars($order['id']) ?></th>
                <td><?php echo htmlspecialchars($order['order_date']) ?></td>
                <td><?php echo htmlspecialchars($order['status']) ?></td>
                <td><?php echo htmlspecialchars($order['total_price']) ?> €</td>
                <td><?php echo htmlspecialchars($order['count_products']) ?></td>
                <td>
                    <a href="order_products.php?id=<?php echo htmlspecialchars($order['id']) ?>" class="btn btn-success">Show</a>
                    <?php if ($order['status'] == 'Done'): ?>
                        <a href="download_invoice.php?file=<?php echo $order['invoice_pdf'] ?>" class="btn btn-danger">Download Invoice</a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($order['status'] == 1): ?>
                        <form action="" method="POST">
                            <button name="order_done" class="btn btn-warning">Mark as Done</button>
                            <button name="order_cancel" class="btn btn-danger">Cancel</button>
                            <input type="hidden" value="<?php echo htmlspecialchars($order['id']) ?>" name="order_id">
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-outline-success" disabled>Done</button>
                    <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>



<?php require_once '../app/templates/footer.php'; ?>