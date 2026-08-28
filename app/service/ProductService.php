<?php

class ProductService{

    public function __construct(private readonly ProductModel $productModel)
    {}
    public function index()
    {
        return $this->productModel->index();
    }

    public function show($id)
    {
        return $this->productModel->show($id);
    }

    public function getProductById($id)
    {
        return $this->productModel->show($id);
    }
    public function getStartseiteProducts()
    {
        return $this->productModel->getStartseiteProducts();
    }
    public function getPageProducts($page, $limit)
    {
        return $this->productModel->getPageProducts($page,$limit);
    }
    public function getProductsBySearch($search)
    {
        return $this->productModel->getProductsBySearch($search);
    }
    public function getProductCount()
    {
        return $this->productModel->getProductCount();
    }
}

?>