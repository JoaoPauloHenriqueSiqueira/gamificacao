<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Scrap extends Model
{
    protected $collection = 'scraps';
    protected $fillable = [
        'company_id', 'user_id',  'message', 'valid_from'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            if(Auth::user()){
                $query->user_id = Auth::user()->id;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('m/d/Y');
    }
}
