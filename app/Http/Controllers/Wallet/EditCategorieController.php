<?php

namespace App\Http\Controllers\Wallet;
use App\Models\User;
use App\Http\Requests\Wallet\EditCategorieRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditCategorieController extends Controller
{
    public function editCategorie(EditCategorieRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $oldCategorieName = $request->input('oldCategorieName');
        $newCategorieName = $request->input('newCategorieName');
        $Isincome=$request->input('isIncome');
       
       
        if ($Isincome) {
            $incomes=$user->wallet['incomes'] ;
            
            // check if new category name already exists
            foreach ($incomes as $category) {
                if ($category['name'] == $newCategorieName) {
                    return response()->json(['error' => 'Category already exists'], 400);
                }
            }
     
            foreach ($incomes as  $category) {
       
                if ($category['name']==$oldCategorieName){
                    $category['name']=$newCategorieName;
                }
            }
            $wallet = $user->wallet;
            $wallet['incomes'] = $incomes;
            $user->wallet = $wallet;
            $user->save();
        
        } else {
    
            $outcomes=$user->wallet['outcomes'];
    
            // check if new category name already exists
            foreach ($outcomes as $category) {
                if ($category['name'] == $newCategorieName) {
                    return response()->json(['error' => 'Category already exists'], 400);
                }
            }
            
            foreach ($outcomes as $key => $category) {
                
                if ($category['name']==$oldCategorieName){
                    $outcomes[$key]['name'] = $newCategorieName;
                }
            }
            $wallet = $user->wallet;
            $wallet['outcomes'] = $outcomes;
            $user->wallet = $wallet;
            $user->save();
        }
      
        try {
            $user->save();
            $success['status'] = true;
            return response()->json($success, 200);
        } catch (Exception $exception) {
            return response()->json(['error' => 'Unable to add category to wallet'], 500);
        }
    }
    
}
