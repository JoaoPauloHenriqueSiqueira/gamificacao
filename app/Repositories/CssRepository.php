<?php

namespace App\Repositories;

use App\Css;
use App\Repositories\Contracts\CssRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class CssRepository extends BaseRepository implements CssRepositoryInterface
{
    public function model()
    {
        return Css::class;
    }
}
