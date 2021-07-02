<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Collection;

class AlbumTransformer
{
    public function transform($albums, $isVideo = false)
    {
        $objs = [];
        foreach ($albums as &$album) {

            $i = 0;
            
            foreach ($album->photos as $u) {
                $i++;
            }

            if ($i == 0) {
                continue;
            }

            $newObject = [];
            $newObject['id'] = $album->id;
            $newObject['name'] = $album->name;
            $newObject['background'] = $album->background;
            $newObject['duration_frames'] = $album->duration_frames;
            $newObject['is_continuous'] =  $album->is_continuous;
            $newObject['days_week'] = $album->days_week;
            $newObject['valid_at'] = $album->valid_at;
            $newObject['valid_from'] = $album->valid_from;
            $newObject['is_video'] = $isVideo;
            $newObject['is_birthday'] = $isVideo;
            $newObject['slides'] = (new PhotoTransformer)->transform($album->photos);
            array_push($objs, $newObject);
        }
        return Collection::make($objs);
    }
}
