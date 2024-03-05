<?php

namespace App\Helpers;
use App\Helpers\Currency\CurrencyFactory;


class NotifyMessage {

    public function failedTransaction($user, $amount){
        $formatter = CurrencyFactory::getFormatter("BRL");
        $realAmount = $formatter->format($amount);
        return "A transferência para o usuário {$user} falhou e o valor de {$realAmount} foi retornado para sua conta.";
    }

    public function successTransaction($userFromName, $userToName, $amount){
        $formatter = CurrencyFactory::getFormatter("BRL");
        $realAmount = $formatter->format($amount);
        return "Olá, {$userToName}, Você recebeu uma transferência de {$userFromName} no valor de {$realAmount}";
    }
}
