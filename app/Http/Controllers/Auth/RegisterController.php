<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Http\Requests\Auth\RegistrationRequest;
use App\Notifications\RegisterNotification;
use App\Notifications\EmailVerificationNotification;


class RegisterController extends Controller
{
    public function register(RegistrationRequest $request)
    {
        $newUser = $request->validated();
        $user = User::where('email', $newUser['email'])->first();
      
       
        $newUser['password'] = Hash::make($newUser['password']);
        $taggg = Tag::where('_id', new \MongoDB\BSON\ObjectId($request->tagId))->first();
        $uid = $taggg ? $taggg->uid : null;
        $tags = [
            [
                'uid'=>$uid,
                'tagId' => $newUser['tagId'],
            ]
        ];
        $newUser['tags'] = $tags;

        $user = User::create($newUser);

        $success['token'] = $user->createToken('auth-token')->plainTextToken;
       

        $success['status'] = true;
        $success['email'] = $user->email;
        $user->notify(new EmailVerificationNotification());
        $tag = Tag::where('_id', new \MongoDB\BSON\ObjectId($request->tagId))->first();


        $tag->disponibility = false;
        $tag->save();

        return response()->json($success, 200);
    }
}
