<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Services\User\UserService;
use Exception;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function find($userId){
        try{
            $res = $this->userService->findById($userId);
        }catch(Exception $e){
            return response()->error($e);
        }
        return response()->success("Usuário encontrado", $res);
    }
    public function store(UserStoreRequest $request){
        $user = $request->validated();

        try{
            $user = $this->userService->store($user);
        }catch(Exception $e){
            return response()->error($e);
        }

        return response()->success("Usuário cadastrado com sucesso", $user);
    }

    public function delete($userId){
        try{
            $res = $this->userService->delete($userId);
        }catch(Exception $e){
            return response()->error($e);
        }
        return response()->success('Usuário deletado com sucesso', $res);
    }

    public function update(UserUpdateRequest $request){
        $data = $request->validated();

        try{
            $user = $this->userService->update($data['id'], $data);

        }catch(Exception $e){
            return response()->error($e);
        }

        return response()->success("Usuário atualizado com sucesso",$user);
    }
}
