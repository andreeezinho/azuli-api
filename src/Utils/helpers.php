<?php

function dd($data){
    echo '<pre>';
    
    var_dump($data);
    
    echo '</pre>';
    die();
}

function totalPrice(?array $products, float $discount = 0) : float {
    if(is_null($products)){
        return 0;
    }
    
    $total = 0;

    foreach($products as $product){
        if(isset($product->quantidade)){
            $total += $product->produto()->preco * $product->quantidade;
        }


        if(!isset($product->quantidade)){
            $total = $total + $product->produto()->preco;
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

function validateEANCode($ean){
    $sumEvenIndexes = 0;
    $sumOddIndexes  = 0;

    $eanAsArray = array_map('intval', str_split($ean));

    if(count($eanAsArray) != 13) {
        return null;
    };

    for ($i = 0; $i < count($eanAsArray)-1; $i++) {
        if ($i % 2 === 0) {
            $sumOddIndexes  += $eanAsArray[$i];
        } else {
            $sumEvenIndexes += $eanAsArray[$i];
        }
    }

    $rest = ($sumOddIndexes + (3 * $sumEvenIndexes)) % 10;

    if ($rest !== 0) {
        $rest = 10 - $rest;
    }

    return $rest === $eanAsArray[12] ? $ean : null;
}

function textFormat($texto){
    $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);

    return preg_replace('/[^A-Za-z0-9 ]/', '', $texto);
}

function onlyNumbers($texto){
    $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);

    return preg_replace('/[^A-Za-z0-9]/', '', $texto);
}

function numberFormat($number, $dec = 2){
    if(is_null($number) || $number == 0){
        return number_format(0, $dec, ".", "");
    }

    return number_format((float)$number, $dec, ".", "");
}