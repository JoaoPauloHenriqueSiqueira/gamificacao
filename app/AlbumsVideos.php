<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AlbumsVideos extends Model
{
    protected $fillable = [
        'name',
        'valid_at',
        'valid_from',
        'active',
        'background',
        'is_continuous',
        'days_week',
        'company_id'
    ];

    protected $casts = [
        'days_week' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->company_id = Auth::user()->company_id;
        });
    }

    public function videos()
    {
        return $this->belongsToMany(Videos::class, 'albums_videos_videos', 'album_video_id', 'video_id');
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
