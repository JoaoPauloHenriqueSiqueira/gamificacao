<?php

namespace App\Repositories;

use App\Repositories\Contracts\ScrapRepositoryInterface;
use App\Scrap;
use Prettus\Repository\Eloquent\BaseRepository;

class ScrapRepository extends BaseRepository implements ScrapRepositoryInterface
{
    public function model()
    {
        return Scrap::class;
    }
}
