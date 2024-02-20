<?php

namespace App\Services\User;
use App\Repository\Contracts\User\IUserRepository;

class UserService{
    public $userRepository;

    public function __construct(IUserRepository $userRepository){
        $this->userRepository = $userRepository;
    }
    public function findById(string $userId){
        return $this->userRepository->findById($userId);
    }
    public function store(array $data){
        return $this->userRepository->store($data);
    }
    public function delete(string $email){
        return $this->userRepository->delete($email);
    }
    public function update(array $data){
        return $this->userRepository->update($data);
    }
}
