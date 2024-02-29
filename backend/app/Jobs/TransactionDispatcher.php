<?php

namespace App\Jobs;

use App\Services\Transaction\TransactionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;
use Illuminate\Http\Client\Factory as Http;
class TransactionDispatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userFromId;
    public $userToId;
    public $amount;
    public $transactionService;
    /**
     * Create a new job instance.
     */
    public function __construct($userFromId, $userToId, $amount)
    {
        $this->userFromId = $userFromId;
        $this->userToId = $userToId;
        $this->amount = $amount;
    }

    private $message;
    private $receiver;
    public $tries = 3;

    /**
     * Execute the job.
     */
    public function handle(Http $http, TransactionService $transactionService): void
    {
        $transaction = $transactionService->processTransaction($this->userFromId, $this->userToId, $this->amount);

        info(json_encode($transaction));
    }

    public function failed(Throwable $throwable){
        $transactionService = app(TransactionService::class);
        $transactionService->processFailedTransaction($this->userFromId, $this->amount);
        info($throwable->getMessage());
    }

    public function backoff(){
        return [3, 3, 3];
    }
}
