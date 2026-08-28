<?php 

class CartController
{
    public function __construct(private readonly CartService $cartService){
        
    }
    public function store($request)
    {
        return $this->cartService->addToCart($request);
               
    }

    public function index()
    {
        return $this->cartService->getCartProducts();
    }

    public function update($request): void
    {
        $this->cartService->update($request);
    }
    
    public function destroy($product_id) : void
    {
        unset($_SESSION['cart'][$product_id]);
    }
}
?>