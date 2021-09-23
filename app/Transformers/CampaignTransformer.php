<?php

namespace App\Transformers;

use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;

class CampaignTransformer
{
    public function transform($campaigns, $users = [], $isVideo = false)
    {
        $objs = [];
        foreach ($campaigns as &$campaign) {

            if($campaign->is_birthday){
                $campaign->users = $users;
            }

            $i = 0;
            
            foreach ($campaign->users as $u) {
                $i++;
            }

            if ($i == 0) {
                continue;
            }

            $newObject = [];
            $newObject['id'] = $campaign->id;
            $newObject['name'] = $campaign->name;
            $newObject['background'] = $campaign->background;
            $newObject['duration_frames'] = $campaign->duration_frames;
            $newObject['is_continuous'] =  $campaign->is_continuous;
            $newObject['days_week'] = $campaign->days_week;
            $newObject['valid_at'] = $campaign->valid_at;
            $newObject['valid_from'] = $campaign->valid_from;
            $newObject['is_video'] = $isVideo;
            $newObject['is_birthday'] = $campaign->is_birthday;
            $newObject['slides'] = (new UserTransformer)->transform($campaign->users);
            array_push($objs, $newObject);
        }
        return Collection::make($objs);
    }
}
