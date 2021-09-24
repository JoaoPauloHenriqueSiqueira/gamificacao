<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Css extends Model
{
    protected $collection = 'css';
    protected $table = 'css';

    protected $fillable = [
        'name', 'value'
    ];
}
