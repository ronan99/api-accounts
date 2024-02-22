<?php

namespace App\Services\Transaction;
use App\Enums\UserType;
use App\Repository\Contracts\Transaction\ITransactionRepository;
use App\Repository\Contracts\User\IUserRepository;
use Exception;
use Http;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class TransactionService{
    public $transactionRepository;
    public $userRepository;
    protected $apiUrl = "https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc";

    public function __construct(ITransactionRepository $transactionRepository, IUserRepository $userRepository){
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
    }

    public function transferBalance(int $userFromId, int $userToId, int $amount){
        DB::beginTransaction();
        try{
            $userFrom = $this->userRepository->findAndLock($userFromId);

            if($userFrom->id == $userToId){
                throw new Exception("Não pode ser feita transferência para o mesmo usuário");
            }
            if($userFrom->type == UserType::MERCHANT){
                throw new Exception("Lojistas não podem fazer transferências");
            }
            if($userFrom->balance < $amount){
                throw new Exception("Saldo insuficiente para operação");
            }


            $userTo = $this->userRepository->findAndLock($userToId);

            $this->userRepository->incrementBalance($userTo->id, $amount);
            $this->userRepository->decrementBalance($userFrom->id, $amount);

            $result = $this->transactionRepository->transferBalance($userFrom->id, $userTo->id, $amount);

            $resAuthorized = $this->apiAuth();

            if(empty($resAuthorized['message']) || $resAuthorized['message'] != "Autorizado"){
                throw new Exception("Transferência não autorizada");
            }

        }catch(\Throwable $e){
            DB::rollBack();
            if($e instanceof QueryException){
                if($e->getCode() == 40001 && !empty($e->errorInfo) && $e->errorInfo[1] == 1213){
                    throw new Exception("Já existe uma transação em andamento");
                }
            }
            throw $e;
        }

        DB::commit();

        return $result;
    }

    public function apiAuth(){
        try {
            $response = Http::get($this->apiUrl);
        } catch (Exception $e) {
            throw new Exception("Transferência não autorizada");
        }

        return $response->json();
    }

}
