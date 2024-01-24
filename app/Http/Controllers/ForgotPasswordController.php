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
    // /**
    //  * Show the forget password form.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */

    // /**
    //  * Submit the forget password form.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\JsonResponse
    //  */
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

    /**
     * Show the reset password form.
     *
     * @param  string  $token
 
     */
    public function showResetPasswordForm($token)
    {
        return view('resetPassword', ['token' => $token]);
    }

    /**
     * Submit the reset password form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
