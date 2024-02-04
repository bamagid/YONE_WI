<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
    public function register(): void
    {
        $this->reportable(function (QueryException $e) {
            Log::error("Une exception de base de données s'est produite: " . $e->getMessage());
            return response()->json([
                'error' => "Une erreur s'est produite au niveau de la base de données: ". $e->getMessage()
            ],$e->getSatusCode());
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'error' => "La methode  ".$request->getMethod()." n'est pas autorisé pour la route " . $request->url(),
            ],$e->getSatusCode());
        });
        $this->renderable(function (RouteNotFoundException $e, $request) {
            return response()->json([
                'error' =>'La route ' .  $request->url() .' n\'a pas été trouvé',
            ],$e->getSatusCode());
        });
        $this->reportable(function (\Illuminate\Validation\ValidationException $e) {
            Log::error("Une exception s'est produite lors de la validation des données: " . $e->getMessage());
        });

        $this->reportable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Une exception de modèle non trouvée s'est produite: " . $e->getMessage());
        });

        $this->renderable(function (QueryException $e, $request) {
            return response()->json([
                'message' => 'Erreur de base de données lors du rendu.',
                'details' => $e->getMessage(),
                'url' => $request->url()
            ], 500);
        });

        $this->renderable(function (ModelNotFoundException $e, $request) {
            return response()->json([
                'error' => 'Erreur de modèle non trouvée lors du rendu.',
                'details' => $e->getMessage(),
                'url' => $request->url()
            ], 404);
        });
        $this->reportable(function (QueryException $e) {
            Log::error("Une exception de base de données s'est produite: " . $e->getMessage());
            return response()->json([
                'error' => 'Une erreur de base de données s\'est produite. Veuillez réessayer plus tard.'
            ], 500);
        });

        $this->reportable(function (\Illuminate\Validation\ValidationException $e) {
            Log::error("Une exception de validation s'est produite: " . $e->getMessage());
        });

        $this->reportable(function (ModelNotFoundException $e) {
            Log::error("Une exception de modèle non trouvée s'est produite: " . $e->getMessage());
        });

        $this->reportable(function (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            Log::error("Une exception de fichier non trouvé s'est produite: " . $e->getMessage());
        });

        $this->reportable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
            Log::error("Une exception de route non trouvée s'est produite: " . $e->getMessage());
        });


        $this->reportable(function (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            Log::error("Une exception de méthode HTTP non autorisée s'est produite: " . $e->getMessage());
        });

        $this->reportable(function (\Illuminate\Session\TokenMismatchException $e) {
            Log::error("Une exception de token CSRF invalide s'est produite: " . $e->getMessage());
        });
        $this->reportable(function (DatabaseNotAvailableException $e) {
            Log::error("La base de données n'est pas disponible: " . $e->getMessage());
        });

        $this->reportable(function (ServerNotAvailableException $e) {
            Log::error("Le serveur n'est pas disponible: " . $e->getMessage());
        });
    }
   
}
