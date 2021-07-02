<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CampaignValidator extends FormRequest
{
    
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        $valid =  [
            'name' => 'required|max:255'
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
