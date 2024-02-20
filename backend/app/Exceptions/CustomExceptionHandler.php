<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class CustomExceptionHandler extends ExceptionHandler
{
    public function render($request, \Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return response()->json(['error' => 'Not Found'], 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(['error' => 'Method not allowed'], 405);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }

        if($exception instanceof QueryException){
            return response()->json(['error' => 'Um problema ocorreu na conexão com o banco. Código: ' . $exception->errorInfo[1],
        "e" => $exception->getMessage()], 500);
        }

        return parent::render($request, $exception);
    }
}
