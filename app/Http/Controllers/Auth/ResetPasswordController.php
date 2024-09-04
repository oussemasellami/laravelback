<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Notifications\ResetPasswordVerificationNotification;
use App\Models\User;
use Otp;
use Hash;
use Illuminate\Support\Facades\Hash as FacadesHash;

class ResetPasswordController extends Controller
{
    private $otp;
    public function __construct()
    {
        $this->otp=new Otp;
    }
   
    public function passwordReset(ResetPasswordRequest $request)
    {
        $otp2=$this->otp->validate($request->email,$request->otp);
        if(!$otp2->status)
        {
            return response()->json(['error'=>$otp2],401);

        }
        $user = User::where('email',$request->email)->first();
        $user->update(['password'=>Hash::make($request->password)]);
        $user->tokens()->delete();
        $success['status']=true;
        return response()->json($success,200);
    }
}
