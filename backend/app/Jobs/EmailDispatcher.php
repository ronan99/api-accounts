<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Client\Factory as Http;
use Throwable;

class EmailDispatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $providerUrl = "https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6";
    private $message;
    private $receiver;
    public $tries = 5;

    /**
     * Create a new job instance.
     */
    public function __construct($message, $receiver)
    {
        $this->message = $message;
        $this->receiver = $receiver;
    }
    /**
     * Execute the job.
     */
    public function handle(Http $http): void
    {

        $http->get($this->providerUrl);
        info("Email enviado para {$this->receiver}, com conteúdo: ". $this->message);

    }

    public function failed(Throwable $throwable){
        info($throwable->getMessage());
    }

    public function backoff(){
        return [3, 10, 15];
    }
}
