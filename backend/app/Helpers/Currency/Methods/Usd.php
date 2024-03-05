<?php

namespace App\Helpers\Currency\Methods;
use App\Helpers\Currency\Interface\ICurrency;

class Usd implements ICurrency{

    public function format(int $value){
        $amountInReal = $value / 100; // Convert cents to Real

        $formattedAmount = '$ ' . number_format($amountInReal, 2, '.', ',');

        return $formattedAmount;
    }
}
