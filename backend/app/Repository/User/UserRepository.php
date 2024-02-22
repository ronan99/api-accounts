<?php

namespace App\Repository\User;

use App\Models\User;
use App\Repository\Contracts\User\IUserRepository;
use Exception;
use Illuminate\Support\Facades\Hash;
class UserRepository implements IUserRepository {
    protected $model;

    public function __construct(User $user){
        $this->model = $user;
    }

    public function findById(string $userId){

        return $this->model->find($userId);
    }

    public function store(array $data){
        try {
            $user = $this->model->create($data, [
                'password' => Hash::make($data['password'])
            ]);
        } catch (Exception $e) {
            if($e->getCode() == 23000){
                throw new Exception("Um problema ocorreu ao tentar salvar o usuário.");
            }
            throw $e;
        }
        return $user;
    }
    public function delete(int $userId){

        $user = $this->model->find($userId);

        if(!$user){
            throw new Exception("Usuário não encontrado");
        }
        if(!$user->delete()){
            throw new Exception("Ocorreu um erro ao deletar o usuário");
        }
        return $user;
    }
    public function update(string $userId, array $data){
        $user = $this->model->find($userId);

        if(!$user){
            throw new Exception("Usuário não encontrado");
        }
        if(!$user->update($data)){
            throw new Exception("Ocorreu um erro ao tentar atualizar o usuário");
        }
        return $user;
    }

    public function decrementBalance($userId, $amount){
        $user = $this->findById($userId);
        $user->balance -= $amount;
        return $user->save();
    }
    public function incrementBalance($userId, $amount){
        $user = $this->findById($userId);
        $user->balance += $amount;
        return $user->save();
    }

    public function findAndLock(string $userId){
        return $this->model->where('id', '=', $userId)->lockForUpdate()->first();
    }
}
