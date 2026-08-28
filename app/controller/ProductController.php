<?php

class ProductController
{

    private int $limit;
    public function __construct(private readonly ProductService $productService, $limit=16)
    {
        $this->limit = $limit;

    }
    public function index()
    {
        $response  = $this->productService->index();
        return $response;
    }
    public function search(string $search)
    {
        return $this->productService->getProductsBySearch($search);
    }

    public function show($id)
    {
        return $this->productService->show($id);
    }

    public function  getPageProducts($page) :array {
        $products = $this->productService->getPageProducts($page,$this->limit);

        $productCount = $this->productService->getProductCount();
        return [
            'products' => $products,
            'pageCount' => (int) ceil($productCount/$this->limit)
        ];

    }


}