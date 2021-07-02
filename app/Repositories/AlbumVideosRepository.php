<?php

namespace App\Repositories;

use App\AlbumsVideos;
use App\Repositories\Contracts\AlbumVideosRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class AlbumVideosRepository extends BaseRepository implements AlbumVideosRepositoryInterface
{
    public function model()
    {
        return AlbumsVideos::class;
    }
}
