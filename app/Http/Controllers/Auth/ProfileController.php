<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $validateData = $request->validated();


        if ($request->password != null) {
            $validateData['password'] = Hash::make($request->password);
        }


        if (array_key_exists('wallet', $validateData) && array_key_exists('name', $validateData['wallet'])) {
            $wallet = $user->wallet; // get the user's current wallet
            $wallet['name'] = $validateData['wallet']['name']; // update the name field
            $user->wallet = $wallet; // set the updated wallet
            unset($validateData['wallet']); // remove the 'wallet' key from the validated data
        }
        $user->update($validateData);

        $user = $user->refresh();

        $success['user'] = $user;
        $success['success'] = true;
        return response()->json($success, 200);
    }
}
