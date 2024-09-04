<?php

namespace App\Http\Controllers\Wallet;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\CategorieHistoryRequest;

use App\Models\User;
use Illuminate\Http\Request;

class CategorieHistoryController extends Controller
{
    public function categorieHistory(CategorieHistoryRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $Isincome=$request->input('isIncome');
        $totalBalance = 0;
       
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
         if ($category['name']==$request->categorieName){
            $transactions = $category['transactions'];
         
            foreach ($transactions as $transaction) {
                $totalBalance += $transaction['amount'];
            }
          
          
         }
       
      
        
       
    
        
            

        }
 
        
            
     try {
        $incomeOroutcomeName=$Isincome?'incomes':'outcomes';
        $success['transactions'] =$transactions; 
     $success['totalBalance']=$totalBalance;
       
     
            // $success[$incomeOroutcomeName] = $Isincome?$incomes:$outcomes;
            $success['status'] = true;
       
            return response()->json($success, 200);
        } catch (Exception $exception) {
            return response()->json(['error' => 'Unable to add category to wallet'], 500);
        }
    }


    }

