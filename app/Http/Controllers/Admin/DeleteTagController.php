<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteTagRequest;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class DeleteTagController extends Controller
{
    public function deleteTag(DeleteTagRequest $request)
    {
        try {
            $tag = Tag::where('uid', $request->uid)->first();

            if (!$tag) {
                return response()->json(['status' => false, 'message' => 'Tag not found'], 404);
            }

            $tag->delete();

            return response()->json(['status' => true], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'An error occurred'], 500);
        }
    }
}