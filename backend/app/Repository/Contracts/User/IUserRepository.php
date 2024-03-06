<?php

namespace App\Repository\Contracts\User;
use User;
interface IUserRepository {

    public function findById(string $userId);

    public function store(array $data);
    public function delete(int $userId);
    public function update(string $userId, array $data);

    public function decrementBalance(string $userId,int $amount);
    public function incrementBalance(string $userId,int $amount);

    public function findAndLock(string $userId);
}
