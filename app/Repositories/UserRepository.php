<?php

namespace App\Repositories;

use App\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function model()
    {
        return User::class;
    }
}
