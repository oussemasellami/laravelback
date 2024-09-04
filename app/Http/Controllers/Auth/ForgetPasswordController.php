<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Notifications\ResetPasswordVerificationNotification;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ForgetPasswordController extends Controller
{
   public function forgetPassword(ForgetPasswordRequest $request)
   {
      // $input = $request->only('email');

      $user = User::where('email', $request->email)->first();

      $notification = new ResetPasswordVerificationNotification();
      $user->notify($notification);
      $success['status'] = true;
      $success['otp'] = Cache::get('otp:' . $user->id);


      return response()->json($success, 200);
   }
}
