<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionTransferBalanceRequest;
use App\Services\Transaction\TransactionService;
use Exception;

class TransactionController extends Controller
{
    protected $user;
    public function __construct(protected TransactionService $transactionService){
        $this->user = auth()->userOrFail();
    }

    public function transferBalance(TransactionTransferBalanceRequest $request){
        $req = $request->validated();

        try {
            $transaction = $this->transactionService->transferBalance($this->user->id, $req['userTo'], $req['amount']);
        }catch(Exception $e){
            return response()->error($e);
        }
        return response()->success("Transação concluída", $transaction);
    }

}
