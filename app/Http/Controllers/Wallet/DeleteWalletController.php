<?php

namespace App\Http\Controllers\Wallet;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Wallet\DeleteWalletRequest;
class DeleteWalletController extends Controller
{
    public function deleteWallet(DeleteWalletRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $currency=$user->wallet['currency'];
        $wallet = [
            'name' => 'wallet name',
            'currency' => $currency,
            'totalBalance'=>0,
            'incomes'=>[],
            'outcomes'=>[]
        ];
    
        $user->wallet = $wallet;
        try {
            $user->save();
            
        $success['status'] = true;
        $success['currency']=$currency;
        return response()->json($success, 200);
        } catch (Exception $exception) {
            return response()->json(['error' => 'Unable to create wallet'], 500);
        }
    }
}
