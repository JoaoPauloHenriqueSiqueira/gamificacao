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

        $isContinuous = true;
        if ($this->request->get('is_not_continuous')) {
            $isContinuous = false;
        }

        if ($isContinuous) {
            $valid['days_week'] = 'required|array|min:1';
        }

        if(!$isContinuous){
            $valid['valid_at'] = 'required';
            $valid['valid_from'] = 'required';
        }

        return $valid;
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome é um campo necessário',
            'days_week.required' => 'É necessário escolher ao menos um dia na semana para campanhas ou álbuns contínuas',
            'valid_at.required' => 'É necessário informar a data de início de exibição',
            'valid_from.required' => 'É necessário informar a data de término de exibição'
        ];
    }
}
