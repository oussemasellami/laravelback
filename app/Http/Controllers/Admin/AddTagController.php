<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddTagRequest;
use Illuminate\Http\Request;
use App\Models\Tag;
use MongoDB\Driver\Exception\BulkWriteException;

class AddTagController extends Controller
{
    public function addTag(AddTagRequest $request)
    {
        try {


            $existingTag = Tag::where('uid', $request->uid)->first();
            if ($existingTag) {
                return response()->json(['status' => false, 'message' => 'Tag already exists'], 422);
            }
            // add UID, example UID: "C8:68:D3:D0:9C:DF"
            $tag = new Tag();
      
            $tag->uid = $request->uid; // Add UID to the Tag model
            $tag->disponibility = true;
            $tag->save();

            return response()->json(['status' => true], 200);
        } catch (BulkWriteException $e) {
            $errorCode = $e->getWriteResult()->getWriteErrors()[0]->getCode();

            if ($errorCode === 11000) {
                return response()->json(['status' => false, 'message' => 'Tag already exists'], 409);
            } else {
                return response()->json(['status' => false, 'message' => 'Error creating tag'], 500);
            }
        }
    }
}