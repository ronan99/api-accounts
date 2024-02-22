<?php

namespace App\Repository\Contracts\Transaction;
interface ITransactionRepository {

    public function transferBalance(int $fromId, int $toId, int $amount);
}
