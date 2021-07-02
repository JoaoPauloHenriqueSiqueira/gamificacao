<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Collection;

class PhotoTransformer
{
    public function transform($photos)
    {
        $objs = [];
        foreach ($photos as &$photo) {
            $newObject = [];
            $newObject['photo'] = $photo->path;
            array_push($objs, $newObject);
        }
        return Collection::make($objs);
    }
}
