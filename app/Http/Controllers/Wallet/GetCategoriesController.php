<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\GetCategoriesRequest;
use Illuminate\Http\Request;
use App\Models\User;

class GetCategoriesController extends Controller
{
    public function getCategories(GetCategoriesRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $Isincome=$request->input('isIncome');
        $totalExpensesOrIncome = 0;
       
        if ($Isincome) {
            $wallet = $user->wallet;
       
            $incomes=$wallet['incomes'] ;
            $categories=$incomes;
        
        } else {
            $wallet = $user->wallet;
            $outcomes=$wallet['outcomes'];
            $categories=$outcomes;
        
            
        };
          foreach ($categories as $category) {
            $transactions = $category['transactions'];
    
            foreach ($transactions as $transaction) {
                $amount = $transaction['amount'];
                $totalExpensesOrIncome += $amount;
            }
            

        }
     
        
            
     try {
        
        $incomeOroutcomeName=$Isincome?'incomes':'outcomes';
$success['totalBalance']=$user->wallet['totalBalance'];
        $success['categories'] =$categories; 
     $success['totalExpensesOrIncome']=$totalExpensesOrIncome;
       
     
            // $success[$incomeOroutcomeName] = $Isincome?$incomes:$outcomes;
            $success['status'] = true;
       
            return response()->json($success, 200);
        } catch (Exception $exception) {
            return response()->json(['error' => 'Unable to add category to wallet'], 500);
        }
    }
}
