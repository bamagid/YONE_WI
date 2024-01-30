<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;




/**
 * @OA\Info(
 *     title="Api Yone wi",
 *     description="Cet api permet de gerer des reseaux de transports en commun au senegal et le partage d'informations sur les les abonnements ,trajets et leurs tarifs  ",
 *     version="1.0.0"
 * ),
 *
 * @OA\Security(
 *     security={
 *         "BearerAuth": {}
 *     }
 * ),
 *
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * ),
 * @OA\Consumes({
 *     "multipart/form-data"
 * }
 * )
 */




class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
