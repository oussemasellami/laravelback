<?php

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function checkAvailability(Request $request)
    {
        try {
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
