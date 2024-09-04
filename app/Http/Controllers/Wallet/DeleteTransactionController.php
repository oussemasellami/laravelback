<?php

namespace App\Http\Controllers\Wallet;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Wallet\DeleteTransactionRequest;

class DeleteTransactionController extends Controller
{
    public function deleteTransaction(DeleteTransactionRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $amount=0;
        $categorieName = $request->input('categoryName');
        $isIncome = $request->input('isIncome');
        $transactionDates = $request->input('transactionDates');
        if ($isIncome) {
            $incomes = $user->wallet['incomes'];
            foreach ($incomes as $categoryKey => $category) {
                if ($category['name'] == $categorieName) {
                    $transactions = $category['transactions'];
                    foreach ($transactions as $transactionKey => $transaction) {
                        if (in_array($transaction['date'], $transactionDates)) { // check if transaction date is in the list
                            $amount+=$transaction['amount'];
                            unset($transactions[$transactionKey]);
                        }
                    }
                    $transactions = array_values($transactions); // reindex the array
                    $incomes[$categoryKey]['transactions'] = $transactions;
                    $incomes[$categoryKey]['balance'] = array_reduce($transactions, function ($carry, $transaction) {
                        return $carry + $transaction['amount'];
                    }, 0);
                    break;
                }
            }
            $wallet = $user->wallet;
            $wallet['totalBalance'] -= $category['balance'];
            $wallet['incomes'] = $incomes;
            $user->wallet = $wallet;
        }else {
            $outcomes = $user->wallet['outcomes'];
            foreach ($outcomes as $categoryKey => $category) {
                if ($category['name'] == $categorieName) {
                    $transactions = $category['transactions'];
                    foreach ($transactions as $transactionKey => $transaction) {
                        if (in_array($transaction['date'], $transactionDates)) { 
                            // check if transaction date is in the list
                            $amount+=$transaction['amount'];
                            unset($transactions[$transactionKey]);
                           
                        }
                    }
                    $transactions = array_values($transactions); // reindex the array
                    
                    $outcomes[$categoryKey]['transactions'] = $transactions;
                    $outcomes[$categoryKey]['balance'] = array_reduce($transactions, function ($carry, $transaction) {
                        return $carry + $transaction['amount'];
                    }, 0);
                    break;
                }
            }
            $wallet = $user->wallet;
            $wallet['totalBalance'] += $amount;
            $wallet['outcomes'] = $outcomes;
            $user->wallet = $wallet;
        }
    
        try {
            $user->save();
            $success['status'] = true;
            return response()->json($success, 200);
        } catch (Exception $exception) {
            return response()->json(['error' => 'Unable to delete transaction'], 500);
        }
    }
    
}
