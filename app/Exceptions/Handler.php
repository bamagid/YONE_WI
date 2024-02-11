<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use PDOException;
use BadMethodCallException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function render($request, Throwable $exception)
    {
        $response = ['error' => $exception->getMessage()];
        if ($exception instanceof HttpException) {
            return response()->json(
                app()->environment('local') ? $response : ['error' => "Httpexception"],
                $exception->getStatusCode()
            );
        } elseif (
            $exception instanceof QueryException ||
            $exception instanceof PDOException
        ) {
            return response()->json(
                app()->environment('local') ?
                    $response : ["error" => "Query exception"],
                500
            );
        } elseif ($exception instanceof BadMethodCallException) {
            return response()->json( app()->environment('local') ?
            $response : ["error" => "Bad method call"], 405);
        } elseif ($exception instanceof ModelNotFoundException) {
            return response()->json( app()->environment('local') ?
            $response : ["error" => "Model Not Found"], 404);
        } elseif (
            $exception instanceof AuthorizationException
        ) {
            return response()->json($response, 403);
        }
        return parent::render($request, $exception);
    }
}
