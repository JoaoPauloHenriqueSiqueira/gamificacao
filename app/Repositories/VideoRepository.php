<?php

namespace App\Repositories;

use App\Photos;
use App\Repositories\Contracts\VideoRepositoryInterface;
use App\Videos;
use Prettus\Repository\Eloquent\BaseRepository;

class VideoRepository extends BaseRepository implements VideoRepositoryInterface
{
    public function model()
    {
        return Videos::class;
    }
}
