<?php

namespace App\Services\Transaction;
use App\Enums\UserType;
use App\Helpers\Currency;
use App\Helpers\NotifyMessage;
use App\Jobs\EmailDispatcher;
use App\Jobs\TransactionDispatcher;
use App\Repository\Contracts\Transaction\ITransactionRepository;
use App\Repository\Contracts\User\IUserRepository;
use Exception;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\QueryException;
use Throwable;

class TransactionService{

    protected $apiUrl = "https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc";

    public function __construct(
        protected ITransactionRepository $transactionRepository,
        protected IUserRepository $userRepository,
        protected DatabaseManager $database,
        protected HttpClient $httpClient,
        protected NotifyMessage $notifyMessage
        ){

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

            $this->userRepository->decrementBalance($userFrom->id, $amount);
            $resAuthorized = $this->apiAuth();

            if(empty($resAuthorized['message']) || $resAuthorized['message'] != "Autorizado"){
                throw new Exception("Transferência não autorizada");
            }

            TransactionDispatcher::dispatch($userFromId, $userToId, $amount);

        }catch(Throwable $e){
            $this->database->rollBack();
            if($this->isDeadlock($e)){
                throw new Exception("Já existe uma transação em andamento", 409);
            }
            throw $e;
        }
        $this->database->commit();

        return ['success' => true, 'message' => "Saldo transferido"];
    }

    public function processTransaction($userFromId, $userToId, $amount){
        $this->database->beginTransaction();
        try{

            $userTo = $this->userRepository->findAndLock($userToId);
            $userFrom = $this->userRepository->findAndLock($userFromId);

            $this->userRepository->incrementBalance($userTo->id, $amount);

            $result = $this->transactionRepository->transferBalance($userFrom->id, $userTo->id, $amount);

        }catch(Throwable $e){
            $this->database->rollBack();
            if($this->isDeadlock($e)){
                throw new Exception("Já existe uma transação em andamento", 409);
            }
            throw $e;
        }
        $this->database->commit();
        EmailDispatcher::dispatch($this->notifyMessage->successTransaction($userFrom->name, $userTo->name, $amount), $userTo->name);

        return $result;
    }

    public function processFailedTransaction($userId, $amount){
        $this->database->beginTransaction();
        try{

            $userFrom = $this->userRepository->findAndLock($userId);

            $this->userRepository->incrementBalance($userFrom->id, $amount);

        }catch(Throwable $e){
            $this->database->rollBack();
            if($this->isDeadlock($e)){
                throw new Exception("Já existe uma transação em andamento", 409);
            }
            throw $e;
        }
        $this->database->commit();
        EmailDispatcher::dispatch($this->notifyMessage->failedTransaction($userFrom->name, $amount), $userFrom->name);

        return true;
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

}
