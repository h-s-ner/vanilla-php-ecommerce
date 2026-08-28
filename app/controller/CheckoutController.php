<?php
require_once '../app/model/UserModel.php';
require_once '../app/core/Middleware.php';
require_once '../app/controller/CartController.php';

class CheckoutController extends CartController{


    public function  __construct(private readonly CheckoutService $checkoutService, private readonly CartService $cartService)
    {
        parent::__construct($cartService);

    }
    public function checkout($request) : void {

        if (isset($request['checkout'])) {
            $errors = $this->checkoutService->checkout($request);

            if (is_array($errors))
            {
                header('location: /cart.php');
                exit();
            }
            //REMOVE THE CART FROM THE SESSION
            unset($_SESSION['cart']);


            //REDIRECT TO THE ORDERS PAGE
            header('location: /orders.php');
            exit();
        }


    }
}