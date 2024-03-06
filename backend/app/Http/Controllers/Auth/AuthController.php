<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Exception;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(AuthRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            $token = auth()->attempt($credentials);

            if (!$token) {
                throw new Exception('Não autorizado', 401);
            }
        }catch(Exception $e){
            return response()->error($e);
        }

        return $this->respondWithToken($token);
    }

    public function me(): JsonResponse
    {
        return response()->success("Logado", auth()->user());
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->success('Deslogado com sucesso');
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->success("Logado com sucesso",[
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
