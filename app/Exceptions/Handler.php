<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use PDOException;

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
    // public function render($request, Throwable $exception)
    // {
  
    //     return response()->json([
    //         'error' => $exception->getMessage(),
    //     ],$exception->getStatusCode() ?: 500);
    // }
    // /**
    //  * Register the exception handling callbacks for the application.
    //  */
    // public function register(): void
    // {
    //     $this->renderable(function (Throwable $e) {
    //         return response()->json([
    //             'error' => $e->getMessage(),
    //         ],$e->getStatusCode() ? : 500);
    //     });
    // }
    
   
}
