<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GetTagsRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tag;

class GetTagsController extends Controller
{
    public function getTags(GetTagsRequest $request)
    {
        $tags = Tag::all();

       
    $tags = tag::all();

  
    $tagData = $tags->map(function ($tag) {
        return [
            'id' => $tag->id,
            'disponibility' => $tag->disponibility,
            'uid'=>$tag->uid
        ];
    });

    return response()->json(['tags' => $tagData]);

        return response()->json(['tags' => $tagData]);
    }
}
