<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Collection;

class ScrapTransformer
{
    public function transform($scraps)
    {
        $objs = [];
        foreach ($scraps as &$scrap) {
            $newObject = [];
            $newObject['id'] = $scrap->id;
            $newObject['text'] = $scrap->message;
            $newObject['created_at'] =  $scrap->created_at;
            $newObject['user'] = (new ScrapUserTransformer)->transform($scrap->user);

            array_push($objs, $newObject);
        }
        return Collection::make($objs)->shuffle();
    }
}
