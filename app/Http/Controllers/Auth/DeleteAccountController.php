<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\DeleteAccountRequest;
use App\Http\Controllers\Controller;

class DeleteAccountController extends Controller
{
    public function deleteAccount(DeleteAccountRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }


        $tagId = null;
        $tags = $user->tags;
        if (count($tags) > 0) {
            $tagId = $tags[0]['tagId'];
        }
        echo ($tagId);
        $tag = Tag::where('_id', new \MongoDB\BSON\ObjectId($tagId))->first();


        $tag->disponibility = true;
        $tag->save();
        $user->save();
        
        $user->delete();

        return response()->json(['message' => 'Profile deleted.'], 200);
    }
}
