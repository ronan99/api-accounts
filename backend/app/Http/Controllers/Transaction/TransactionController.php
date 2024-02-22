<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionTransferBalanceRequest;
use App\Services\Transaction\TransactionService;

class TransactionController extends Controller
{
    protected $transactionService;
    protected $user;
    public function __construct(TransactionService $transactionService){
        $this->transactionService = $transactionService;
        $this->user = auth()->userOrFail();
    }

    public function transferBalance(TransactionTransferBalanceRequest $request){
        $req = $request->validated();

        try {
            $transaction = $this->transactionService->transferBalance($this->user->id, $req['userTo'], $req['amount']);
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return $transaction;
    }

}
