<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $collection = 'companies';
    protected $fillable = [
        'name', 'cpf', 'active', 'chat', 'phone',
        'logo', 'token_screen', 'background_default', 'logo', 'password_default',
        'postalCode', 'district', 'street', 'number', 'city', 'state', 'country',
    ];
}
