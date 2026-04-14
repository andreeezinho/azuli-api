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
        $total -= ($total * $discount) / 100;
    }

    return $total;
}

function calculateTroco(float $total, float $troco, float $received) : float {
    if($troco < 0){
        $troco -= $received;

        return $troco;
    }

    if($troco == 0){
        $total -= $received;

        return $total;
    }

    $total = $total - (($total - $troco) + $received);

    return $total;
}