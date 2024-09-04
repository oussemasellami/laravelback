<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class AddExpenseOrIncomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'=>['required','email','exists:users'],
            'isIncome'=>'required|boolean',
            'categorie'=>'required|string',
            'amount'=>'required|numeric',
            'description' => 'required|string',
            'date'=>'required|date',
            
        ];
    }
}
