<?php

namespace App\Http;

use OpenApi\Annotations as OA;

class Annotations
{

    /**
     * @OA\Security(
     *     security={
     *         "BearerAuth": {}
     *     },
     */


    /**
     * @OA\SecurityScheme(
     *     securityScheme="BearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT"
     */


    /**
     * @OA\Info(
     *     title="Your API Title",
     *     description="Your API Description",
     *     version="1.0.0"
     */


    /**
     * @OA\Consumes({
     *     "multipart/form-data"
     * })
     */


    /**
     * @OA\POST(
     *     path="/api/forget-password",
     *     summary="Demade de reinitialisation de mot de passe",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="email", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de compte"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/reset-password",
     *     summary="Reinitialisation de mot de passe",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="password", type="string"),
     *                     @OA\Property(property="password_confirmation", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de compte"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/logout",
     *     summary="deconnexion",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion de compte"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/refresh",
     *     summary="Rafraichir le token",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion de compte"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/users/{user}",
     *     summary="Modification de compte",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="path", name="user", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="prenom", type="string"),
     *                     @OA\Property(property="adresse", type="string"),
     *                     @OA\Property(property="telephone", type="string"),
     *                     @OA\Property(property="etat", type="string"),
     *                     @OA\Property(property="reseau_id", type="string"),
     *                     @OA\Property(property="image", type="string", format="binary"),
     *                     @OA\Property(property="motif", type="string"),
     *                     @OA\Property(property="password", type="string"),
     *                     @OA\Property(property="password_confirmation", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de compte"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/updateadmin",
     *     summary="Modification de compte admin",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="prenom", type="string"),
     *                     @OA\Property(property="password", type="string"),
     *                     @OA\Property(property="password_confirmation", type="string"),
     *                     @OA\Property(property="image", type="string", format="binary"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de compte"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/profile",
     *     summary="afficher l'utilisateur connecté ",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion de compte"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/users",
     *     summary="Creation d'un admin reseau",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="prenom", type="string"),
     *                     @OA\Property(property="adresse", type="string"),
     *                     @OA\Property(property="telephone", type="string"),
     *                     @OA\Property(property="role_id", type="string"),
     *                     @OA\Property(property="reseau_id", type="string"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="image", type="string", format="binary"),
     *                     @OA\Property(property="password", type="string"),
     *                     @OA\Property(property="password_confirmation", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des utilisateurs"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/users",
     *     summary="Lister les utilisateurs",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des utilisateurs"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/users/etat/2",
     *     summary="Bloquer ou debloquer  un utilisateur",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="user", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="motif", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des utilisateurs"},
     * )
     */


    /**
     * @OA\DELETE(
     *     path="/api/users/1",
     *     summary="supprimé",
     *     description="",
     * @OA\Response(response="204", description="Deleted successfully")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     * @OA\Response(response="404", description="Not Found")
     *     @OA\Parameter(in="path", name="user", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="motif", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des utilisateurs"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/roles/empty-trash",
     *     summary="vider les roles qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des roles"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/roles/deleted",
     *     summary="Lister les roles qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des roles"},
     * )
     */


    /**
     * @OA\PUT(
     *     path="/api/roles/restaurer/{role}",
     *     summary="restaurer",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="role", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des roles"},
     * )
     */


    /**
     * @OA\PUT(
     *     path="/api/roles/delete/{role}",
     *     summary="supprimer un role dans la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="role", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des roles"},
     * )
     */


    /**
     * @OA\DELETE(
     *     path="/api/roles/{role}",
     *     summary="supprimé",
     *     description="",
     * @OA\Response(response="204", description="Deleted successfully")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     * @OA\Response(response="404", description="Not Found")
     *     @OA\Parameter(in="path", name="role", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des roles"},
     * )
     */


    /**
     * @OA\PUT(
     *     path="/api/roles/{role}",
     *     summary="Modifier un role",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="role", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des roles"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/roles",
     *     summary="Lister les roles",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des roles"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/roles",
     *     summary="Ajouter un role",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des roles"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/reseaus/empty-trash",
     *     summary="vider les reseaux qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion de reseaux"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/reseaus/deleted/all",
     *     summary="Lister les reseaux qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion de reseaux"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/reseaus/restaurer/{reseau}",
     *     summary="restaurer un reseau",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="reseau", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion de reseaux"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/reseaus/delete/{reseau}",
     *     summary="supprimer un reseau de la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="reseau", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion de reseaux"},
     * )
     */


    /**
     * @OA\DELETE(
     *     path="/api/reseaus/{reseau}",
     *     summary="Supprimer un reseau",
     *     description="",
     * @OA\Response(response="204", description="Deleted successfully")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     * @OA\Response(response="404", description="Not Found")
     *     @OA\Parameter(in="path", name="reseau", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion de reseaux"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/reseaus/{reseau}",
     *     summary="Afficher un reseau",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="reseau", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion de reseaux"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/reseaus/{reseau}",
     *     summary="Modifier un reseau",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="reseau", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de reseaux"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/reseaus",
     *     summary="Lister les reseaux",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion de reseaux"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/reseaus",
     *     summary="Ajouter un reseau",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de reseaux"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/reseau/description",
     *     summary="Modifier la description d'un reseau",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="reseau", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="description", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de reseaux"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/lignes/empty-trash",
     *     summary="vider les lignes qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des lignes"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/lignes/deleted/all",
     *     summary="Lister les lignes qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des lignes"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/lignes/restaurer/{ligne}",
     *     summary="restaurer une ligne",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des lignes"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/lignes/delete/{ligne}",
     *     summary="supprimer une ligne de la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des lignes"},
     * )
     */


    /**
     * @OA\DELETE(
     *     path="/api/lignes/{ligne}",
     *     summary="Supprimer une ligne",
     *     description="",
     * @OA\Response(response="204", description="Deleted successfully")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     * @OA\Response(response="404", description="Not Found")
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des lignes"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/lignes/{ligne}",
     *     summary="Afficher une ligne",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des lignes"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/lignes/{ligne}",
     *     summary="Modifier une ligne",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="ligne", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="type_id", type="integer"),
     *                     @OA\Property(property="lieuDepart", type="string"),
     *                     @OA\Property(property="lieuArrivee", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des lignes"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/lignes",
     *     summary="Lister les lignes",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des lignes"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/lignes",
     *     summary="Ajouter une ligne",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="type", type="string"),
     *                     @OA\Property(property="type_id", type="integer"),
     *                     @OA\Property(property="lieuDepart", type="string"),
     *                     @OA\Property(property="lieuArrivee", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des lignes"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/newsletter/subscribe",
     *     summary="souscrire a la newsletter",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="email", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des newsletters"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/newsletter/unscribe",
     *     summary="Se desabonner  a la newsletter",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="email", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des newsletters"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/newsletter/all",
     *     summary="lister les personnes inscrits sur les newsletter",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des newsletters"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/contacts",
     *     summary="Lister les contacts",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des contacts"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/contacts",
     *     summary="Ajouter un contact",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="sujet", type="string"),
     *                     @OA\Property(property="contenu", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des contacts"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/abonnements/empty-trash",
     *     summary="vider les abonnements qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des abonnements"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/abonnements/deleted/all",
     *     summary="Lister les abonnements qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des abonnements"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/abonnements/restaurer/{abonnement}",
     *     summary="restaurer un abonnement",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="abonnement", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des abonnements"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/abonnements/delete/{abonnement}",
     *     summary="supprimer un abonnement de la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="abonnement", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des abonnements"},
     * )
     */


    /**
     * @OA\DELETE(
     *     path="/api/abonnements/{abonnement}",
     *     summary="Supprimer un abonnement",
     *     description="",
     * @OA\Response(response="204", description="Deleted successfully")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     * @OA\Response(response="404", description="Not Found")
     *     @OA\Parameter(in="path", name="abonnement", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des abonnements"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/abonnements/{abonnement}",
     *     summary="Afficher un abonnement",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="abonnement", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des abonnements"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/abonnements/{abonnement}",
     *     summary="Modifier un abonnements",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="abonnement", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="prix", type="integer"),
     *                     @OA\Property(property="type", type="string"),
     *                     @OA\Property(property="duree", type="string"),
     *                     @OA\Property(property="etat", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des abonnements"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/abonnements",
     *     summary="Lister les abonnements",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des abonnements"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/abonnements",
     *     summary="Ajouter un abonnements",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="prix", type="integer"),
     *                     @OA\Property(property="type", type="string"),
     *                     @OA\Property(property="duree", type="string"),
     *                     @OA\Property(property="etat", type="string"),
     *                     @OA\Property(property="description", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des abonnements"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/tarifs/deleted/all",
     *     summary="Lister les tarifs qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des tarifs"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/tarifs/empty-trash",
     *     summary="vider les tarifs qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des tarifs"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/tarifs/restaurer/{tarif}",
     *     summary="restaurer un tarif",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="tarif", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des tarifs"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/tarifs/delete/{tarif}",
     *     summary="supprimer un tarif de la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="tarif", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des tarifs"},
     * )
     */


    /**
     * @OA\DELETE(
     *     path="/api/tarifs/{tarif}",
     *     summary="Supprimer un tarif",
     *     description="",
     * @OA\Response(response="204", description="Deleted successfully")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     * @OA\Response(response="404", description="Not Found")
     *     @OA\Parameter(in="path", name="tarif", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des tarifs"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/tarifs/{tarif}",
     *     summary="Afficher un tarif",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="{tarif}", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des tarifs"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/tarifs/{tarif}",
     *     summary="Modifier un tarif",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="tarif", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="prix", type="integer"),
     *                     @OA\Property(property="type", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des tarifs"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/tarifs",
     *     summary="Lister les tarifs",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des tarifs"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/tarifs",
     *     summary="Ajouter un tarif",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="prix", type="integer"),
     *                     @OA\Property(property="type", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des tarifs"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/sections/empty-trash",
     *     summary="vider les sections qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des sections"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/sections/deleted/all",
     *     summary="Lister les sections qui sont dans la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des sections"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/sections/restaurer/{section}",
     *     summary="restaurer un section",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="section", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des sections"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/sections/delete/{section}",
     *     summary="supprimer  un section de la corbeille",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="section", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des sections"},
     * )
     */


    /**
     * @OA\DELETE(
     *     path="/api/sections/{section}",
     *     summary="Supprimer un section",
     *     description="",
     * @OA\Response(response="204", description="Deleted successfully")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     * @OA\Response(response="404", description="Not Found")
     *     @OA\Parameter(in="path", name="section", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des sections"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/sections/{section}",
     *     summary="Afficher un section",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="section", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des sections"},
     * )
     */


    /**
     * @OA\PATCH(
     *     path="/api/sections/{section}",
     *     summary="Modifier un section",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="path", name="section", required=false, @OA\Schema(type="string")
     * )
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="Depart", type="string"),
     *                     @OA\Property(property="Arrivee", type="string"),
     *                     @OA\Property(property="ligne_id", type="integer"),
     *                     @OA\Property(property="tarif_id", type="integer"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des sections"},
     * )
     */


    /**
     * @OA\GET(
     *     path="/api/sections",
     *     summary="Lister les sections",
     *     description="",
     * @OA\Response(response="200", description="OK")
     * @OA\Response(response="404", description="Not Found")
     * @OA\Response(response="500", description="Internal Server Error")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     tags={"Gestion des sections"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/sections",
     *     summary="Ajouter un section",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="Depart", type="string"),
     *                     @OA\Property(property="Arrivee", type="string"),
     *                     @OA\Property(property="ligne_id", type="integer"),
     *                     @OA\Property(property="tarif_id", type="integer"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion des sections"},
     * )
     */


    /**
     * @OA\POST(
     *     path="/api/login",
     *     summary="connexion",
     *     description="",
     * @OA\Response(response="201", description="Created successfully")
     * @OA\Response(response="400", description="Bad Request")
     * @OA\Response(response="401", description="Unauthenticated")
     * @OA\Response(response="403", description="Unauthorize")
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * )
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="password", type="string"),
     *                 },
     *             ),
     *         ),
     *     ),
     *     tags={"Gestion de l'authentification"},
     * )
     */
}
