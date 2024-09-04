<?php

namespace App\Http\Controllers\Wallet;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\CreateWalletRequest;
use Illuminate\Http\Request;

class CreateWallet extends Controller
{
    public function createWallet(CreateWalletRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        $wallet = [
            'name' => $request->name,
            'currency' => $request->currency,
            'totalBalance'=>0,
            'incomes'=>[],
            'outcomes'=>[]
        ];
    
        $user->wallet = $wallet;
    
        try {
            $user->save();
            
        $success['status'] = true;
        $success['walletName']=$request->name;
        return response()->json($success, 200);
        } catch (Exception $exception) {
            return response()->json(['error' => 'Unable to create wallet'], 500);
        }
    
    }
    
}
