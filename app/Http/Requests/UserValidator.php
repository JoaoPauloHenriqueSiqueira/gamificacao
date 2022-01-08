<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'email' => 'required|unique:users,email|max:255',
            'password' => 'required|min:5|max:20'
        ];

        $companyId = Auth::user()->company_id;

        $email = is_null($this->request->get('email'));
        $admin = $this->request->get('admin');

        // //se admin email precisa ser único
        // if ($admin && !$email) {
        //     $valid['email'] =  'required|unique:users,email|max:255';
        // }

        // //se não é admin, email único pra minha empresa
        // if (!$admin && !$email) {
        //     $valid['email'] = Rule::unique('users')
        //         ->ignore($this->request->get('id'))->where(function ($query) use ($companyId) {
        //             return $query->where('company_id', $companyId);
        //         });
        // }

        $today = new Carbon();
        $maxDate = new Carbon();
        $maxDate->subYears(120);

        $valid['birthday'] = 'date|after_or_equal:' . $maxDate . '|before_or_equal:' . $today;

        return $valid;
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome é um campo necessário',
            'email.unique' => 'Um usuário já possui cadastro com esse email',
            'email.required' => 'Email é um campo necessário',
            'password.required' => 'Senha Padrão é um campo necessário',
            'password.min' => "São necessários 5 caracteres para o campo senha",
            'password.max' => "São necessários até 20 caracteres para o campo senha",
            'birthday.required' => 'Data de Nascimento é um campo necessário',
            'birthday.after_or_equal' => "Data de nascimento precisa ser superior ao ano de 1901",
            'birthday.before_or_equal' => "Data de nascimento precisa ser inferior dia de hoje",
        ];
    }
}
