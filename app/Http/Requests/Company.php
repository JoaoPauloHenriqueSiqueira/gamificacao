<?php

namespace App\Http\Requests;

use App\Library\Format;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Company extends FormRequest
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

        $cpf = is_null($this->request->get('cpf'));
        if (!$cpf) {
            $valid['cpf'] = Rule::unique('companies')->ignore($this->request->get('id'))->where(function ($query) {
                return $query->where('id', Auth::user()->company_id);
            }) . "|min:11|max:14";
        }

        $valid['name'] = 'unique:companies,name,' .  Auth::user()->company_id;

        $password = is_null($this->request->get('password_default'));
        if (!$password) {
            $valid['password_default'] = 'min:5|max:20';
        }


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
            'password_default.min' => "São necessários 5 caracteres para o campo senha",
            'password_default.max' => "São necessários até 20 caracteres para o campo senha",
            'name.min' => 'Mínimo de 1 letras para um nome',
            'name.max' => 'Máximo de 30 letras para um nome',
            'name.unique' => "Nome de empresa já em uso",
            'cpf.min' => 'Mínimo de 11 dígitos para CPF',
            'cpf.max' => 'Máximo de 14 dígitos para CPF',
            'cpf.unique' => "CPF já possui cadastro",
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
