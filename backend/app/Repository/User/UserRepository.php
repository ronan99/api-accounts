<?php

namespace App\Repository\User;

use App\Models\User;
use App\Repository\Contracts\User\IUserRepository;
use Illuminate\Support\Facades\Hash;
class UserRepository implements IUserRepository {
    protected $model;

    public function __construct(User $user){
        $this->model = $user;
    }

    public function findById(string $id){

        return $this->model->find($id);
    }

    public function store(array $data){
        try {
            $user = $this->model->create($data, [
                'password' => Hash::make($data['password'])
            ]);
        } catch (\Exception $e) {
            if($e->getCode() == 23000){
                throw new \Exception("Um problema ocorreu ao tentar salvar o usuário.");
            }
            throw $e;
        }
        return $user;
    }
    public function delete(string $id){

        return $this->model->where(["email" => $id])->delete();
    }
}
