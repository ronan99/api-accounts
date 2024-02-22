<?php

namespace App\Services\User;
use App\Repository\Contracts\User\IUserRepository;
use Exception;

class UserService{
    public $userRepository;

    public function __construct(IUserRepository $userRepository){
        $this->userRepository = $userRepository;
    }
    public function findById(string $userId){
        $user = $this->userRepository->findById($userId);
        if(empty($user)){
            throw new Exception("Usuário não encontrado", 404);
        }

        return $user;
    }
    public function store(array $data){
        return $this->userRepository->store($data);
    }
    public function delete(int $userId){
        return $this->userRepository->delete($userId);
    }
    public function update(int $userId, array $data){
        return $this->userRepository->update($userId, $data);
    }
}
