<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\User\UserService;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function find($id){
        try{
            $res = $this->userService->findById($id);
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return $res;
    }
    public function store(UserRequest $request){
        $user = $request->validated();

        try{
            $user = $this->userService->store($user);

        }catch(\Exception $e){
            return $e->getMessage();
        }

        return $user;
    }

    public function delete($email){
        try{
            $res = $this->userService->delete($email);
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return $res;
    }
}
