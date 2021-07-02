<?php

namespace App\Repositories;

use App\Campaign;
use App\Repositories\Contracts\CampaignRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class CampaignRepository extends BaseRepository implements CampaignRepositoryInterface
{
    public function model()
    {
        return Campaign::class;
    }
}
