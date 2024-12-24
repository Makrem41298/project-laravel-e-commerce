<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;

use App\Models\Employ;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthEmployeController extends Controller
{
    public function __construct() {
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->guard('employs')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        return $this->createNewToken($token);
    }

    public function logout() {
        auth()->guard('employs')->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }
    public function refresh() {
        return $this->createNewToken(auth()->guard('employs')->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->guard('employs')->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('employs')->factory()->getTTL() * 60,
            'user' => auth()->guard('employs')->user()
        ]);
    }
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:employes',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $status=Password::broker('employes')->sendResetLink(
            $request->only('email')
        );
        return $status===Password::RESET_LINK_SENT
            ?response()->json(['message' => __($status)],200)
            :response()->json(['email' => __($status)],400);


    }
    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:employes',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $status = Password::broker('employes')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Employ $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
            ?response()->json(['message' => __($status)],200)
            :response()->json(['message' => __($status)],400);
    }

}
