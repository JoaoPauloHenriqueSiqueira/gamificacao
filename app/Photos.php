<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Photos extends Model
{
    protected $collection = 'photos';
    protected $fillable = ['path'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->company_id = Auth::user()->company_id;
        });
    }

    public function albums()
    {
        return $this->belongsToMany(Album::class, 'albums_photos');
    }

}
