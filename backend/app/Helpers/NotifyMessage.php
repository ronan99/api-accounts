<?php

namespace App\Helpers;


class NotifyMessage {

    public function __construct(protected Currency $currencyFormat){}
    public function failedTransaction($user, $amount){

        $realAmount = $this->currencyFormat->formatToReal($amount);
        return "A transferência para o usuário {$user} falhou e o valor de {$realAmount} foi retornado para sua conta.";
    }

    public function successTransaction($userFromName, $userToName, $amount){
        $realAmount = $this->currencyFormat->formatToReal($amount);
        return "Olá, {$userToName}, Você recebeu uma transferência de {$userFromName} no valor de {$realAmount}";
    }
}
