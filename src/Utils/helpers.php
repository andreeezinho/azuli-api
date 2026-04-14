<?php

function dd($data){
    echo '<pre>';
    
    var_dump($data);
    
    echo '</pre>';
    die();
}

function totalPrice(array $products, float $discount = 0) : float {
    $total = 0;

    foreach($products as $product){
        if(isset($product->quantidade)){
            $total += $product->preco * $product->quantidade;
        }


        if(!isset($product->quantidade)){
            $total = $total + $product->preco;
        }
    }

    if($discount > 0){
        $total -= $discount;
        $total *= -1;
    }

    return $total;
}