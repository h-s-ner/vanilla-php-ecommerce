<?php
require_once '../app/templates/header.php';
require_once '../app/core/Middleware.php';

Middleware::guardAndRedirect(!Middleware::isAdmin());

?>
<a href="admin_products.php" class="btn btn-primary hs-bg">Products</a>
<a href="admin_users.php" class="btn btn-primary hs-bg">Users</a>
<a href="admin_orders.php" class="btn btn-success hs-bg">Orders</a>


<?php  require_once '../app/templates/footer.php';?>