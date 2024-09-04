<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Faker\Provider\UserAgent;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    //
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email is incorrect'], 401);
        }



        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            if (!$user->hasVerifiedEmail()) {
                /** @var \App\Models\MyUserModel $user **/
                $user = Auth::user();
                // $user->tokens()->delete();
                return response()->json(
                    [
                        'error' => 'Email not verified',

                        'token' => $user->createToken(request()->userAgent())->plainTextToken
                    ],
                    401
                );
            }
            /** @var \App\Models\User $user **/

            $user = Auth::user();

            $user->tokens()->delete();
            // auth('sanctum')->user()->tokens()->delete();

            $success['token'] = $user->createToken(request()->userAgent())->plainTextToken;
            $success['email'] = $request->email;
            $success['status'] = true;
            $success['walletName'] = $user->wallet['name'];
            return response()->json($success, 200);
        } else {
            return response()->json(['error' => 'Password is incorrect'], 401);
        }
    }
}
