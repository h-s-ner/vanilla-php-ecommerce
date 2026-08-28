<?php

class AdminOrderService{
    private $errors = [];

    public function __construct(
        private readonly OrderModel $orderModel)
    {

    }

    public function index()
    {
        return $this->orderModel->adminIndex();
    }
     public function updateStatus(int $order_id , OrderStatus $status)
    {
        return $this->orderModel->updateStatus($order_id, $status->value);
    }

}
?>