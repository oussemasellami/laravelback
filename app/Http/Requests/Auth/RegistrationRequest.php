<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'username'=>'required|max:100',
            'email'=>'required|unique:users,email',
            'phone'=>'required|digits_between:8,20',
            'password'=>'required|min:6',
            'tagId' => 'required|string',

            //
        ];
    }
}
