<?php

namespace App\Http\Controllers\Tag;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class AddTagToUserController extends Controller
{
    public function addTagToUser(Request $request)
    {

        try {
            $user = User::where('email', $request->input('email'))->first();
            $tag = Tag::where('_id', new \MongoDB\BSON\ObjectId($request->input('id')))->first();

            if (!$tag) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tag not found',
                ]);
            }

            if ($tag->disponibility === false) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tag not available',
                ]);
            }

            $tags = $user->tags; // Retrieve the tags array


            array_push($tags, ['uid' => $tag->uid, 'tagId' => (string)$tag->_id]); // Add the new tag to the existing array

            $user->tags = $tags; // Update the user's tags property

            $user->save();

            $tag->disponibility = false;
            $tag->save();
            return response()->json([
                'status' => true,
                'message' => 'Tag available',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
