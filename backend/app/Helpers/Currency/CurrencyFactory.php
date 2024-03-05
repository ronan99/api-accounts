<?php

namespace App\Helpers\Currency;

use App\Helpers\Currency\Interface\ICurrency;
use App\Helpers\Currency\Methods\Usd;
use App\Helpers\Currency\Methods\Real;
use Exception;


class CurrencyFactory {
    protected $formatters = [
        "BRL" => Real::class,
        "USD" => Usd::class
    ];
    public static function getFormatter(String $id): ICurrency{
        switch($id){
            case "BRL":
                return new Real();
            case "USD":
                return new Usd();
            default:
                throw new Exception("Formator desconhecido");
        }
    }
}
