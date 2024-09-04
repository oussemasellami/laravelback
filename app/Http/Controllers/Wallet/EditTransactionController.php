<?php

namespace App\Http\Controllers\Wallet;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\EditTransactionRequest;
use Illuminate\Http\Request;

class EditTransactionController extends Controller
{
    public function modifyTransaction(EditTransactionRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        $categoryName = $request->input('categoryName');
        $isIncome = $request->input('isIncome');
        $transactionDate = $request->input('transactionDate');
        $newDescription = $request->input('description');
        $newAmount = $request->input('amount');
     
        $categories = $isIncome ? $user->wallet['incomes'] : $user->wallet['outcomes'];

        foreach ($categories as $categoryKey => $category) {
            if ($category['name'] == $categoryName) {
                $transactions = $category['transactions'];
                foreach ($transactions as $transactionKey => $transaction) {
                    if ($transaction['date'] == $transactionDate) {
                        if ($newDescription != null) {
                            $transactions[$transactionKey]['description'] = $newDescription;
                        }
                        if ($newAmount != null) {
                            $wallet = $user->wallet;
                            
                         
                            $wallet['totalBalance'] += $isIncome ? ($newAmount - $transaction['amount']) : ($transaction['amount'] - $newAmount);
                            $user->wallet = $wallet;
                            $user->save();
                            $categories[$categoryKey]['balance'] += $newAmount - $transaction['amount'];
                            $transactions[$transactionKey]['amount'] = $newAmount;
                        }
                        $categories[$categoryKey]['transactions'] = $transactions;
                        break 2;
                    }
                }
            }
        }
        
        if ($isIncome) {
            $wallet = $user->wallet;
            $wallet['incomes'] = $categories;
            $user->wallet = $wallet;
            $user->save();
          
        } else {
            $wallet = $user->wallet;
            $wallet['outcomes'] = $categories;
            $user->wallet = $wallet;
            $user->save();
        }

        try {
            $user->save();
            return response()->json(['status' => true], 200);
        } catch (Exception $exception) {
            return response()->json(['error' => 'Unable to modify transaction'], 500);
        }
    }

}
