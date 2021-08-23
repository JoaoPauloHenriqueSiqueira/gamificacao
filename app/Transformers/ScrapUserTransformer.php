<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Collection;

class ScrapUserTransformer
{
    public function transform($user)
    {
        $newObject = [];

        if ($user) {
            $newObject['name'] = $user->name;
            if ($user->photo) {
                $newObject['photo'] = $user->photo;
            }
        }
        return Collection::make($newObject);
    }
}
