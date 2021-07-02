<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'valid_at',
        'valid_from',
        'active',
        'background',
        'duration_frames',
        'is_continuous',
        'days_week',
        'company_id'
    ];

    protected $casts = [
        'days_week' => 'array',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'campaigns_users', 'campaign_id', 'user_id');
    }

    public function getValidAtInputAttribute()
    {
        return Carbon::parse($this->attributes['valid_at'])->format('Y-m-d');
    }

    public function getValidFromInputAttribute()
    {
        return Carbon::parse($this->attributes['valid_from'])->format('Y-m-d');
    }

    public function getValidAtFormatAttribute()
    {
        return Carbon::parse($this->attributes['valid_at'])->format('d/m/Y');
    }

    public function getValidFromFormatAttribute()
    {
        return Carbon::parse($this->attributes['valid_from'])->format('d/m/Y');
    }
}
