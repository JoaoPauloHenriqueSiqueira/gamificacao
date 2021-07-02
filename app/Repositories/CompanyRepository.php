<?php

namespace App\Repositories;

use App\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    public function model()
    {
        return Company::class;
    }
}
