<?php


class OrderController{

    public function __construct(
        private readonly OrderService $orderService
    ){}

    public function getOrdersByUserId(int $user_id)
    {
        return $this->orderService->getOrdersByUserId($user_id);
    }
    public function getOrderData(int $order_id)
    {
        $order = $this->orderService->getOrderById($order_id);
        // Check if order exists
        if (!$order) {
            header('location: index.php');
            exit();
        }
        // Check if order belongs to user or the user is admin
        if ($order['user_id'] != $_SESSION['user']['id']
            && !Middleware::isAdmin()) {
            header('location: index.php');
            exit();
        }

        return [
            'orderProducts'=>$this->orderService->getOrderProducts($order_id),
            'invoiceAddress' => $this->orderService->getOrderInvoiceAdress($order_id),
            'deliveryAddress' => $this->orderService->getOrderDeliveryAddress($order_id)
        ];
    }


}


?>