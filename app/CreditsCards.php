<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreditsCards extends Model
{
    protected $collection = 'credits_cards';
    protected $fillable = [
        'company_id', 'name', 'token', 'cvv', 'cardNumber',
        'brand', 'expirationMonth', 'expirationYear',
        'cardNumber', 'plan_token', 'reference'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->company_id = Auth::user()->company_id;
        });
    }

    public function getNumberCreditCardAttribute()
    {
        $number = $this->attributes['card_number'];
        return substr($number, 0, 4) . str_repeat('*', strlen($number) - 8) . substr($number, -4);
    }
}
