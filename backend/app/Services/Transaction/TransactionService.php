<?php

namespace App\Services\Transaction;
use App\Enums\UserType;
use App\Helpers\Currency;
use App\Jobs\EmailDispatcher;
use App\Repository\Contracts\Transaction\ITransactionRepository;
use App\Repository\Contracts\User\IUserRepository;
use Exception;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;
use Throwable;

class TransactionService{
    public $transactionRepository;
    public $userRepository;
    protected $database;
    protected $httpClient;
    protected $apiUrl = "https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc";

    public function __construct(
        ITransactionRepository $transactionRepository,
        IUserRepository $userRepository,
        DatabaseManager $database,
        HttpClient $httpClient
        ){
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
        $this->database = $database;
        $this->httpClient = $httpClient;
    }

    public function transferBalance(int $userFromId, int $userToId, int $amount){
        $this->database->beginTransaction();
        try{
            $userFrom = $this->userRepository->findAndLock($userFromId);

            if($userFrom->id == $userToId){
                throw new Exception("Não pode ser feita transferência para o mesmo usuário", 400);
            }
            if($userFrom->type == UserType::MERCHANT){
                throw new Exception("Lojistas não podem fazer transferências", 400);
            }
            if($userFrom->balance < $amount){
                throw new Exception("Saldo insuficiente para operação", 402);
            }


            $userTo = $this->userRepository->findAndLock($userToId);

            $this->userRepository->incrementBalance($userTo->id, $amount);
            $this->userRepository->decrementBalance($userFrom->id, $amount);

            $result = $this->transactionRepository->transferBalance($userFrom->id, $userTo->id, $amount);

            $resAuthorized = $this->apiAuth();

            if(empty($resAuthorized['message']) || $resAuthorized['message'] != "Autorizado"){
                throw new Exception("Transferência não autorizada");
            }

        }catch(Throwable $e){
            $this->database->rollBack();
            if($this->isDeadlock($e)){
                throw new Exception("Já existe uma transação em andamento", 409);
            }
            throw $e;
        }
        $this->database->commit();
        $this->dispatchNotificationJob($userFrom, $userTo, $result);

        return $result;
    }

    public function apiAuth(){
        try {
            $response = $this->httpClient->get($this->apiUrl);
        } catch (Exception $e) {
            throw new Exception("Transferência não autorizada");
        }

        return $response->json();
    }

    private function isDeadlock($exception){
        if($exception instanceof QueryException && $exception->getCode() == 40001
         && !empty($exception->errorInfo) && $exception->errorInfo[1] == 1213){
            return true;
        }

        return false;
    }

    private function dispatchNotificationJob($userFrom, $userTo, $result){
        $currency = new Currency();
        $value = $currency->formatToReal($result['amount']);
        $message = "Olá, {$userTo['name']}, Você recebeu uma transferência de {$userFrom['name']} no valor de {$value}";
        EmailDispatcher::dispatch($message, $userTo['name']);
    }

}
