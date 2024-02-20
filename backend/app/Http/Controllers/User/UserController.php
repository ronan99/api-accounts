<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Services\User\UserService;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function find($userId){
        try{
            $res = $this->userService->findById($userId);
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return $res;
    }
    public function store(UserStoreRequest $request){
        $user = $request->validated();

        try{
            $user = $this->userService->store($user);

        }catch(\Exception $e){
            return $e->getMessage();
        }

        return $user;
    }

    public function delete(UserDeleteRequest $request){
        try{
            $res = $this->userService->delete($request->email);
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return $res;
    }

    public function update(UserStoreRequest $request){
        $user = $request->validated();

        try{
            $user = $this->userService->update($user);

        }catch(\Exception $e){
            return $e->getMessage();
        }

        return $user;
    }
}
