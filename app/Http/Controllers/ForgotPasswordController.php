<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ForgotPasswordController extends Controller
{
    /**
     * @OA\POST(
     *     path="/api/forget-password",
     *     summary="Demade de reinitialisation de mot de passe",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
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
     * ),
     */
    public function submitForgetPasswordForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
        Mail::send('PasswordResetMail', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });
        return response()->json([
            'message' => 'Nous vous avons envoyé par mail le lien de réinitialisation du mot de passe !'
        ]);
    }

    public function showResetPasswordForm($token)
    {
        return view('resetPassword', ['token' => $token]);
    }

    /**
     * @OA\POST(
     *     path="/api/reset-password",
     *     summary="Reinitialisation de mot de passe",
     *     description="",
     *  security={
     *   {"BearerAuth": {}},
     *},
     * @OA\Response(response="201", description="Created successfully"),
     * @OA\Response(response="400", description="Bad Request"),
     * @OA\Response(response="401", description="Unauthenticated"),
     * @OA\Response(response="403", description="Unauthorize"),
     *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
     * ),
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
     * ),
     */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'password' => [PasswordRule::default(), 'confirmed'],
        ]);

        $user = DB::table('password_reset_tokens')
            ->where([
                'token' => $request->token,
            ])->first();

        if (!$user) {
            return response()->json([
                'error' => 'Ce jeton de réinitialisation du mot de passe n\'est pas valide.'
            ], 422);
        }
        User::where('email', $user->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where(['token' => $request->token])->delete();

        return response()->json(['message' => 'Votre mot de passe a bien été réinitialisé !']);
    }
}
