<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'username'=>'sometimes|max:100',
            'email'=>'sometimes|unique:users,email,'.$this->user()->id,
            'phone'=>'sometimes|digits_between:8,20',
            'password'=>'sometimes|min:6',
            'wallet.name' => 'sometimes|max:100',
            'image'=>['image','mimes:jpg,png,jpeg,webp','max:2048']
        ];
    }
}
