<?php

namespace App\Http\Requests;

use App\Library\Format;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CompanyRegister extends FormRequest
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
        $valid['cep'] =  'required';
        $valid['phone'] =  'required|min:11|max:11';
        $valid['email'] =  'required|unique:users,email|email|max:255';
        $valid['password'] = 'required|min:5|max:20|confirmed';
        $valid['cpf'] = Rule::unique('companies')->ignore($this->request->get('id'))->where(function ($query) {
            return $query->where('id', Auth::user()->company_id);
        }) . "|min:11|max:11|cpf";

        $minDate = new Carbon();
        $minDate->subYears(18);
        $maxDate = new Carbon();
        $maxDate->subYears(120);

        $valid['birthday'] = 'date|after_or_equal:' . $maxDate . '|before_or_equal:' . $minDate;

        return $valid;
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

    /**
     * Get the error messages that apply to the request parameters.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'phone.min' => "Telefone precisa ser válido e estar no formato correto, por exemplo (99) 99999-9999",
            'phone.max' => "Telefone precisa ser válido e estar no formato correto, por exemplo (99) 99999-9999",
            'cpf.cpf' => "Esse documento precisa ser válido",
            'cpf.unique' => "Essa empresa já está cadastrada em nossa base",
            'cpf.min' => "São necessários 11 caracteres para o campo CPF",
            'cpf.max' => "São necessários 11 caracteres para o campo CPF",
            'email.unique' => "Esse email já está cadastrado em nossa base",
            'email.email' => "Email precisa ser válido",
            'password.min' => "São necessários 5 caracteres para o campo senha",
            'password.max' => "São necessários até 20 caracteres para o campo senha",
            'password.confirmed' => "\"Senha\" e \"Confirmação de Senha\" não são iguais",
            'birthday.required' => 'Data de Nascimento é um campo necessário',
            'birthday.after_or_equal' => "Data de nascimento precisa ser superior ao ano de 1901",
            'birthday.before_or_equal' => "É necessário ter no mínimo 18 anos",
        ];
    }
}
