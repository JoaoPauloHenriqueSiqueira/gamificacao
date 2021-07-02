<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreditsCards extends Model
{
    protected $collection = 'credits_cards';
    protected $fillable = ['company_id' . 'name', 'cvv', 'cardNumber', 'brand', 'expirationMonth', 'expirationYear', 'cardNumber'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->company_id = Auth::user()->company_id;
        });
    }
}
