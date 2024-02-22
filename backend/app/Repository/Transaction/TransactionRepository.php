<?php

namespace App\Repository\Transaction;

use App\Models\Transaction;
use App\Repository\Contracts\Transaction\ITransactionRepository;
use Exception;
class TransactionRepository implements ITransactionRepository {
    protected $model;

    public function __construct(Transaction $transaction){
        $this->model = $transaction;
    }

    public function transferBalance(int $fromId, int $toId, int $amount){
        return $this->model->create([
            'from' => $fromId,
            'to'=> $toId,
            'amount'=> $amount,
        ]);
    }

}
