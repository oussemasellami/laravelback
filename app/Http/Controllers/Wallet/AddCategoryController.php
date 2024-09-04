<?php

namespace App\Http\Controllers\Wallet;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Wallet\AddCategoryRequest;
use App\Models\Category;
use MongoDB\Operation\Update;

class AddCategoryController extends Controller
{
    public function addCategory(AddCategoryRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $category = new Category(
           
           [ 'balance'=> 0,
           'color'=> $request->color,
             'name' => $request->name,
             'icon'=> $request->icon,
             'transactions'=>[]
           ]
        );

        if ($request->isIncome) {

            $incomes = $user->wallet['incomes'];
            foreach ($incomes as $income) {
                if ($income['name'] == $request->name) {
                    return response()->json(['error' => 'Category already exists'], 400);
                }
            }
            array_push($incomes, $category);
            $wallet = $user->wallet;
            $wallet['incomes'] = $incomes;

            $user->wallet = $wallet;

            $user->save();
        } else {
            $outcomes = $user->wallet['outcomes'];
            foreach ($outcomes as $outcome) {
                if ($outcome['name'] == $request->name) {
                    return response()->json(['error' => 'Category already exists'], 400);
                }
            }
            array_push($outcomes, $category);
            $wallet = $user->wallet;
            $wallet['outcomes'] = $outcomes;

            $user->wallet = $wallet;

            $user->save();
        }

        try {

            $user->save();

            $success['user'] = $user;
            $success['status'] = true;
            $success['categoryName'] = $request->name;
            return response()->json($success, 200);
        } catch (Exception $exception) {
            return response()->json(['error' => 'Unable to add category to wallet'], 500);
        }
    }
}
