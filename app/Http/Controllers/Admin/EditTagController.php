<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EditTagRequest;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EditTagController extends Controller
{
    public function editTag(EditTagRequest $request)
    {
        try {
        
            $oldTagUid = $request->input('oldTagUid');
          
            $newTagUid = $request->input('newTagUid');
            $disponibility = $request->boolean('disponibility');
            if($oldTagUid!=$newTagUid)
            {
 // Check if the tag already exists
 $existingTag = Tag::where('uid', $newTagUid)->first();
 if ($existingTag) {
     return response()->json(['status' => false, 'message' => 'Tag already exists'], 422);
 }
            }
           
            
            // Update the tag with new ID, UID, and disponibility
            $tag = Tag::where('uid', $oldTagUid)->firstOrFail();
          
            $tag->uid = $newTagUid;
            $tag->disponibility = $disponibility;
            $tag->save();
            
            return response()->json(['status' => true, 'message' => 'Tag updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Tag not found'], 404);
        }
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 7b70bb196928ed3962fbaf55df054e61cc363caa
