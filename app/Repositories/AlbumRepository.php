<?php

namespace App\Repositories;

use App\Album;
use App\Repositories\Contracts\AlbumRepositoryInterface;
use App\Repositories\Contracts\AlbumVideosRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class AlbumRepository extends BaseRepository implements AlbumRepositoryInterface
{
    public function model()
    {
        return Album::class;
    }
}
