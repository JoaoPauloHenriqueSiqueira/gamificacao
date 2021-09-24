<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Collection;

class CompanyTransformer
{
    public function transform($company)
    {
        $obj = [];
        $obj['id'] = $company['id'];
        $obj['logo'] = $company['logo'];
        $obj['background_default'] = $company['background_default'];
        $obj['token_screen'] = $company['token_screen'];
        $obj['chat'] = $company['chat'];
        $obj['css'] = $company['css'];


        return Collection::make($obj);
    }
}
