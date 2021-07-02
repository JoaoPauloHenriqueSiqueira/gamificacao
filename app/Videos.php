<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Videos extends Model
{
    protected $collection = 'videos';
    protected $fillable = ['path'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->company_id = Auth::user()->company_id;
        });
    }

    public function albums_videos()
    {
        return $this->belongsToMany(AlbumVideos::class, 'albums_videos');
    }

}
