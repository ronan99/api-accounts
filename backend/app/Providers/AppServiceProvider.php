<?php

namespace App\Providers;

use App\Exceptions\CustomExceptionHandler;
use Exception;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            CustomExceptionHandler::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('error' , function (Exception $exception){
            $status = (empty($exception->getCode()) || $exception->getCode() >= 500) ? 500 : $exception->getCode();
            $message = ($exception->getCode() > 500) ? "Um erro interno ocorreu" : $exception->getMessage();
            return Response::json(["message" => $message], $status);
        });

        Response::macro('success' , function (string $message , mixed $data = []){
            $res['message'] = $message;
            if(!empty($data)){
                $res['data'] = $data;
            }

            return Response::json($res);
        });
    }
}
