<?php   

class CartService{

    private array $errors = [];

    public function __construct(private readonly ProductModel $productModel)
    {        
    }

    public function addToCart($request){
        $product_id = $request['product_id'];
        $quantity = $request['quantity'];
        $product = $this->productModel->show($product_id);
        if (!$product || $quantity > $product['stock'])
        {
            return false;
        }
        if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])){
            if ($_SESSION['cart'][$product_id]['quantity'] + $quantity < $product['stock']){
                if ($_SESSION['cart'][$product_id]['quantity'] + $quantity > 30){
                    $_SESSION['cart'][$product_id]['quantity'] = 30;
                }else{
                    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
                }
            }else{
                $_SESSION['cart'][$product_id]['quantity'] = $product['stock'];
            }
        }else{
            $_SESSION['cart'][$product_id] = [
                'product_id' => $product_id,
                'quantity' => $quantity
            ];
        }
        return true;
    }
    
    public function getCartProducts($forCheckout=false){
        $ProductsToShow=[];
        if (isset($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $cartProduct){
                $product_id = $cartProduct['product_id'];
                $product = $this->productModel->show($product_id);
                if (!$product){
                    unset($_SESSION['cart'][$product_id]);
                    continue;
                }
                $ProductsToShow[$product_id]=$product;
                if ($product['stock'] < $cartProduct['quantity']){
                    $ProductsToShow[$product_id]['quantity'] = $product['stock'];
                }
                else{
                    $ProductsToShow[$product_id]['quantity'] = $cartProduct['quantity'];
                }
            }
        }
        return $ProductsToShow;
    }
    public function update($request){
        
        $product_id = $request['product_id'];
        $quantity = $request['quantity'];
        $product = $this->productModel->show($product_id);

        if (!$product || $product['stock'] < $quantity) {
            header('Location: /cart.php');
            exit();
        }
        if (isset($_SESSION['cart'][$product_id])){
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }else{
            header('Location: /cart.php');
            exit();
        }}
}
?>