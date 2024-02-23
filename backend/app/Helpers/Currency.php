<?php

namespace App\Helpers;


class Currency {

    public function formatToReal(int $value){
        $amountInReal = $value / 100; // Convert cents to Real

        $formattedAmount = 'R$ ' . number_format($amountInReal, 2, ',', '.');

        return $formattedAmount;
    }
}
