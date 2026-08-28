<?php require_once __DIR__.'/../db/DB.php';

Class OrderModel extends DB{

    public function adminIndex(): false|array
    {
        $ps = $this->conn->prepare('
            SELECT o.id, o.status, o.order_date, COUNT(op.id) AS count_products, SUM(op.price * op.quantity) AS total_price FROM orders o
            LEFT JOIN order_products op ON op.order_id = o.id
            GROUP BY o.id, o.order_date
            ORDER BY o.order_date DESC
        ');

        $ps->execute();

        return $ps->fetchAll();
    }
    public function updateStatus(int $order_id, int $status): void
    {
        $ps = $this->conn->prepare('
        UPDATE orders
        SET status = :status
        WHERE id = :order_id
        ');
        $ps->bindParam(':order_id', $order_id);
        $ps->bindParam(':status', $status);
        $ps->execute();
    }

    public function storeOrder($user_id, $order_date, $status, $invoice_id, $delivery_id) : false|string
    {
        $sql = "INSERT INTO `orders`(`user_id`, `order_date`, `status`, `invoice_id`, `delivery_id`)
                 VALUES (:user_id,:order_date,:status,:invoice_id,:delivery_id)";
        $ps = $this->conn->prepare($sql);
        $ps->execute([
            ':user_id'=> $user_id,
            ':order_date'=> $order_date,
            ':status'=> $status,
            ':invoice_id'=> $invoice_id,
            ':delivery_id'=>$delivery_id
        ]);
        return $this->conn->lastInsertId();
    }

    public function storeOrderProducts($order_id, $product_id, $quantity, $price): void
    {
        $sql = "
        INSERT INTO order_products (order_id, product_id, quantity, price)
        VALUES (:order_id, :product_id, :quantity, :price)";
        $ps = $this->conn->prepare($sql);
        $ps->execute([
            ':order_id'=> $order_id,
            ':product_id'=>$product_id,
            ':quantity'=> $quantity,
            ':price'=> $price
        ]);
    }

    public function findById($id)
    {
        $ps = $this->conn->prepare('
        SELECT o.id,o.user_id, o.order_date, SUM(op.price * op.quantity) AS total_price FROM orders o
        LEFT JOIN order_products op ON op.order_id = o.id
        WHERE o.id = :order_id
        GROUP BY o.id
        ');
        $ps->bindParam(':order_id', $id, PDO::PARAM_INT);
        $ps->execute();
        return $ps->fetch();
    }

    public function getOrderProducts($order_id): false|array
    {
        $ps = $this->conn->prepare('
        SELECT  p.name AS name , p.image AS image , op.quantity AS quantity, op.price AS price
        FROM order_products op
        LEFT JOIN products p ON p.id = op.product_id
        WHERE op.order_id = :order_id
        ');

        $ps->bindParam(':order_id', $order_id);
        $ps->execute();
        return $ps->fetchAll();
    }
    public function getOrdersByUserId($user_id): false|array
    {
        $ps = $this->conn->prepare('
        SELECT o.id, o.status, o.order_date, COUNT(op.id) AS count_products, SUM(op.price * op.quantity) AS total_price FROM orders o
        LEFT JOIN order_products op ON op.order_id = o.id
        WHERE user_id = :user_id
        GROUP BY o.id, o.order_date
        ORDER BY o.order_date DESC
        ');
        $ps->bindParam(':user_id', $user_id);
        $ps->execute();
        return $ps->fetchAll();
    }
    public function show($order_id)
    {
        $ps = $this->conn->prepare('SELECT * FROM orders WHERE id = :id');
        $ps->bindParam(':id', $order_id, PDO::PARAM_INT);
        $ps->execute();
        return $ps->fetch();
    }
    public function getInvoiceAddress($order_id)
    {
        $ps = $this->conn->prepare("
        SELECT concat_ws(', ', a.address, a.zip, a.city) as InvoiceAddress FROM `addresses` a
        join orders on a.id= orders.invoice_id
        WHERE orders.id = :order_id");
        $ps->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $ps->execute();
        return $ps->fetch();
    }
    public function getDeliveryAddress($order_id)
    {
        $ps = $this->conn->prepare("
        SELECT concat_ws(', ', a.address, a.zip, a.city) as DeliveryAddress FROM `addresses` a
        join orders on a.id= orders.delivery_id
        WHERE orders.id = :order_id");
        $ps->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $ps->execute();
        return $ps->fetch();
    }
}