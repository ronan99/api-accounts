<?php

namespace App\Services\User;
use App\Models\User;
use App\Repository\Contracts\User\IUserRepository;
use Exception;

class UserService{

    public function __construct(protected IUserRepository $userRepository){
    }
    public function findById(string $userId): Array{
        $user = $this->userRepository->findById($userId);
        if(empty($user)){
            throw new Exception("Usuário não encontrado", 404);
        }

        return $user;
    }
    public function store(array $data): User{
        return $this->userRepository->store($data);
    }
    public function delete(int $userId): bool{
        return $this->userRepository->delete($userId);
    }
    public function update(int $userId, array $data): bool{
        return $this->userRepository->update($userId, $data);
    }
}
