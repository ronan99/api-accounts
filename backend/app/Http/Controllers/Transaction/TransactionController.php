<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionTransferBalanceRequest;
use App\Models\User;
use App\Services\Transaction\TransactionService;
use Exception;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    protected User $user;
    public function __construct(protected TransactionService $transactionService){
        $this->user = auth()->userOrFail();
    }

    public function transferBalance(TransactionTransferBalanceRequest $request): JsonResponse{
        $req = $request->validated();

        try {
            $transaction = $this->transactionService->transferBalance($this->user->id, $req['userTo'], $req['amount']);
        }catch(Exception $e){
            return response()->error($e);
        }
        return response()->success("Transação concluída", $transaction);
    }


}
