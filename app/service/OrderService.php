<?php

class OrderService{

    private array $errors = [];

    public function __construct(private readonly OrderModel $orderModel)
    {
    }



    public function getOrdersByUserId($user_id)
    {
        return $this->orderModel->getOrdersByUserId($user_id);
    }
    public function getOrderById(int $order_id) : array|bool {
        return $this->orderModel->show($order_id);
    }
    public function getOrderProducts(int $order_id)
    {
        return $this->orderModel->getOrderProducts($order_id);
    }
    public function getOrderInvoiceAdress(int $order_id)
    {
        return  $this->orderModel->getInvoiceAddress($order_id);
    }
    public function getOrderDeliveryAddress(int $order_id)
    {
        return $this->orderModel->getDeliveryAddress($order_id);
    }
}
?>