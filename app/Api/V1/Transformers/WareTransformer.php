<?php

namespace App\Api\V1\Transformers;

use Carbon\Carbon;
use App\Models\Ware;
use League\Fractal\TransformerAbstract;

class WareTransformer extends TransformerAbstract {

    public function transform(Ware $ware){
   

        return [
        		'id' => (int) $ware->id,
				'name' => (string) $ware->name,
				'article' => (string)  $ware->article,
               
        ];

    }

}