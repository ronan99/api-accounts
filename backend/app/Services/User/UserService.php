<?php

namespace App\Services\User;
use App\Repository\Contracts\User\IUserRepository;

class UserService{
    public $userRepository;

    public function __construct(IUserRepository $userRepository){
        $this->userRepository = $userRepository;
    }
    public function findById(string $id){
        return $this->userRepository->findById($id);
    }
    public function store(array $data){
        return $this->userRepository->store($data);
    }
    public function delete(string $id){
        return $this->userRepository->delete($id);
    }
}
