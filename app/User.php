<?php

namespace App;

use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'company_id', 'admin', 'birthday', 'photo', 'token_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'sales_products');
    }

    public function getBirthdayAttribute()
    {
        if ($this->attributes['birthday']) {
            return Carbon::parse($this->attributes['birthday'])->format('d/m');
        }
    }

    public function getBirthdayDateAttribute()
    {
        if ($this->attributes['birthday']) {

            return Carbon::parse($this->attributes['birthday'])->format('Y-m-d');
        }
    }

    public function getBirthdayCardAttribute()
    {
        if ($this->attributes['birthday']) {

            return Carbon::parse($this->attributes['birthday'])->format('d/m/Y');
        }
    }
}
