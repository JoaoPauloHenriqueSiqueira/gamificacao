<?php

namespace App\Http\Requests;

use App\Library\Format;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreditCard extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $valid = [];
        $valid['name'] = 'required';
        $valid['expirationYear'] = 'required';
        $valid['expirationMonth'] = 'required';
        $valid['cvv'] = 'required';
        $valid['brand'] = 'required';
        $valid['name'] = 'required';
        return $valid;
    }

    /**
     * Get the error messages that apply to the request parameters.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.min' => 'Mínimo de 1 letras para um nome',
            'name.max' => 'Máximo de 30 letras para um nome',
        ];
    }

    public function getValidatorInstance()
    {
        $this->extractNumbers();
        return parent::getValidatorInstance();
    }

    protected function extractNumbers()
    {
        if ($this->request->has('cpf')) {
            $this->merge([
                'cpf' => Format::extractNumbers($this->request->get('cpf'))
            ]);
        }

        if ($this->request->has('phone')) {
            $this->merge([
                'phone' => Format::extractNumbers($this->request->get('phone'))
            ]);
        }
    }
}
