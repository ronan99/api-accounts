<?php

namespace App\Helpers\Currency\Interface;


interface ICurrency {

    public function format(int $value): String;

}
