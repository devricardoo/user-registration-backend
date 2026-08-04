<?php

namespace App\Exceptions;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ModelNotFoundException $exception) {
            $message = $exception->getModel() === User::class
                ? 'Usuário não encontrado.'
                : 'Registro não encontrado.';

            return response()->json([
                'message' => $message,
            ], 404);
        });

        $this->renderable(function (NotFoundHttpException $exception) {
            $previous = $exception->getPrevious();

            if ($previous instanceof ModelNotFoundException) {
                $message = $previous->getModel() === User::class
                    ? 'Usuário não encontrado.'
                    : 'Registro não encontrado.';
            } else {
                $message = $exception->getMessage() ?: 'Recurso não encontrado.';
            }

            return response()->json([
                'message' => $message,
            ], 404);
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
