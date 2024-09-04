<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GetUsersRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tag;

class GetUsersController extends Controller
{
    public function getUsers(GetUsersRequest $request)
    {
        $users = User::all();
        return response()->json($users);
    }
}
  
// $user = $request->user();
// $userData = $user->map(function ($user) {
// $tagData = $user->tags->map(function ($tag) {
//     return [
//         'id' => $tag->id,
//     ];
// });

// return [
//     'name' => $user->username,
//     'email' => $user->email,
//     'phone_number' => $user->phone,
//     'tag_ids' => $tagData,
// ];
// })->toArray();
// return response()->json($userData);