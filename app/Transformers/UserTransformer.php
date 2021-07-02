<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Collection;

class UserTransformer
{
    public function transform($users)
    {
        $objs = [];
        foreach ($users as &$user) {
            $newObject = [];
            $newObject['name'] = $user->name;
            $newObject['photo'] = $user->photo;
            $newObject['birthday'] = $user->birthday;
            array_push($objs, $newObject);
        }
        return Collection::make($objs);
    }
}
