<?php

namespace App\Helpers\Currency\Methods;
use App\Helpers\Currency\Interface\ICurrency;

class Real implements ICurrency{

    public function format(int $value): String{
        $amountInReal = $value / 100; // Convert cents to Real

        $formattedAmount = 'R$ ' . number_format($amountInReal, 2, ',', '.');

        return $formattedAmount;
    }
}
