<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserValidator extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       
        $valid =  [
            'name' => 'required|max:255',
        ];


        return $valid;
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome é um campo necessário'
        ];
    }
}
