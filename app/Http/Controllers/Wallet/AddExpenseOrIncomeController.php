<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\AddExpenseOrIncomeRequest;
use Illuminate\Http\Request;
use App\Models\User;
class AddExpenseOrIncomeController extends Controller
{
    public function addExpenseOrIncome(AddExpenseOrIncomeRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $category = $request->input('categorie');
        $amount = (float) $request->input('amount');
        $description = $request->input('description');
        $date = $request->input('date');
        $transaction=[
            'amount'=>$amount,
            'description'=>$description,
            'date'=>$date
          ];
          if ($request->input('isIncome')) {
            $incomes = $user->wallet['incomes'];
            foreach ($incomes as &$income) {
                if ($income['name'] === $category) {
                    $income['balance'] +=$amount;
                    array_push($income['transactions'],$transaction);
                    break;
                }
            }
           
            $wallet = $user->wallet;
            $wallet['incomes'] = $incomes;
            $wallet['totalBalance']+=$amount;
            $user->wallet = $wallet;
            $user->save();

    } else {
        $outcomes = $user->wallet['outcomes'];
        foreach ($outcomes as &$outcome) {
            if ($outcome['name'] === $category) {
                $outcome['balance'] +=$amount;
                array_push($outcome['transactions'],$transaction);
                break;
            }
        }
        $wallet = $user->wallet;
        $wallet['totalBalance']-=$amount;
        $wallet['outcomes'] = $outcomes;
        $user->wallet = $wallet;
        $user->save();
    }
        try {
            $user->save();
            // $success['wallet']=$user->wallet;
            $success['status'] = true;
            $success['message'] = 'Expense/Income added successfully';
            return response()->json($success, 200);
        } catch (Exception $exception) {
            return response()->json(['error' => 'Unable to add expense/income'], 500);
        }
    }
}
