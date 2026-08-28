<?php   


class CheckoutService {


    private array $errors = [];

    public function __construct(
        private readonly ProductModel $productModel,
        private readonly OrderModel $orderModel
    ){}
    
    private function validateProductStock():void
    {
        foreach ($_SESSION['cart'] as $product_id => $value) {

            $product = $this->productModel->show($product_id);

            if (!$product) {
                unset($_SESSION['cart'][$product_id]);
                $this->errors[] = "Product $product_id not found";
                continue;
            }

            $stock = $value['quantity'];

            if ($product['stock'] < $stock) {
                $this->errors[] = "Product " . $product['name'] . " have max of " . $product['stock'] . " in stock";
                continue;
            }

            // SAVE THE PRODUCT PRICE FORM THE DB TO THE SESSION
            $_SESSION['cart'][$product_id]['price'] = $product['price'];
        }
        //return (count($errors) === 0)? true:$errors; 

    }

    public function checkout($request)
    {
        Middleware::validateCsrfToken();
        Middleware::reGenerateCsrfToken();
        
        //VALIDATE THE PRODUCT STOCK
        $this->validateProductStock();
        if (!empty($this->errors))
        {
            return $this->errors;
        }

        $userId= $_SESSION['user']['id'];
        $invoiceAddressId = $request['invoice_address_id'];
        $deliveryAddressId = $request['delivery_address_id'];
        //CALL THE STORE FUNCTION - 1 is FOR PENDING STATUS
        $now = new DateTime();
        $formated_date = $now->format("Y-m-d H:i:s");
        //CALL THE STORE FUNCTION - 1 is FOR PENDING STATUS
        $orderid = $this->orderModel->storeOrder($userId, $formated_date, 1, intval($invoiceAddressId), intval($deliveryAddressId ));


        //SAVE PRODUCTS IN THE order_products TABLE
        foreach ($_SESSION['cart'] as $product_id => $value) {
            $this->orderModel->storeOrderProducts($orderid, $product_id, $value['quantity'], $value['price']);

            //UPDATE THE PRODUCT STOCK
            $product_stock = $this->productModel->getProductStockById($product_id);

            $new_stock = $product_stock - $value['quantity'];

            $this->productModel->updateStock($product_id, $new_stock);
        }
        
        
    }
}
