<?php

namespace App\Repositories;

use App\Repositories\Contracts\CreditsCardsRepositoryInterface;
use App\CreditsCards;
use Aws\Credentials\CredentialsInterface;
use Prettus\Repository\Eloquent\BaseRepository;

class CreditsCardsRepository extends BaseRepository implements CreditsCardsRepositoryInterface
{
    public function model()
    {
        return CreditsCards::class;
    }
}
