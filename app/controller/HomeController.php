<?php

class HomeController
{

    private array $errors = [];

    public function __construct(private readonly ProductService $productService)
    {

    }
    public function index()
    {
        $products  = $this->productService->getStartseiteProducts();
        $productgroups = array_chunk($products,4);
        /*
        als alternative zu array_chunk
        $count = count($products);
        $productgroups = [];
        $productsubgroups = [];
        $j = 0;
        $i = 1;
        foreach($products as $product){
            $productsubgroups[]=$product;

            if(($i%4==0 && $i>0) || $i == $count)
            {
                $productgroups[$j++]=$productsubgroups;
                $productsubgroups=[];
            }
            $i++;

        }*/
        return $productgroups;
    }


}