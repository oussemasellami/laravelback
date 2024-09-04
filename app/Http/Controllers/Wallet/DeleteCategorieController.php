<?php

namespace App\Http\Controllers\Wallet;
use App\Models\User;
use App\Http\Requests\Wallet\DeleteCategorieRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeleteCategorieController extends Controller
{
    public function deleteCategorie(DeleteCategorieRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $categorieName = $request->input('categorieName');
        $Isincome=$request->input('isIncome');
       
        if ($Isincome) {
            $incomes=$user->wallet['incomes'];
            
            $categoryIndex = null;
            
            // Check if category name exists
            foreach ($incomes as $key => $category) {
                if ($category['name'] == $categorieName) {
                    $categoryIndex = $key;
                    break;
                }
            }
            
            if ($categoryIndex === null) {
                return response()->json(['error' => 'Category does not exist'], 400);
            }
            
            $category = $incomes[$categoryIndex];
            $wallet = $user->wallet;
            $wallet['totalBalance'] -= $category['balance'];
            unset($incomes[$categoryIndex]);
            $wallet['incomes'] = array_values($incomes);
            $user->wallet = $wallet;
        } else {
            $outcomes=$user->wallet['outcomes'];
            
            $categoryIndex = null;
            
            // Check if category name exists
            foreach ($outcomes as $key => $category) {
                if ($category['name'] == $categorieName) {
                    $categoryIndex = $key;
                    break;
                }
            }
            
            if ($categoryIndex === null) {
                return response()->json(['error' => 'Category does not exist'], 400);
            }
            
            $category = $outcomes[$categoryIndex];
            $wallet = $user->wallet;
            $wallet['totalBalance'] += $category['balance'];
            unset($outcomes[$categoryIndex]);
            $wallet['outcomes'] = array_values($outcomes);
            $user->wallet = $wallet;
        }
        
        try {
            $user->save();
            $success['status'] = true;
            return response()->json($success, 200);
        } catch (Exception $exception) {
            return response()->json(['error' => 'Unable to delete category from wallet'], 500);
        }
    }
    

}
