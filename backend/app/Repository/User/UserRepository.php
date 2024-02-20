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
    public function delete(string $email){

        return $this->model->where(["email" => $email])->delete();
    }
    public function update(array $data){
        return $this->model->update($data);
    }
}
