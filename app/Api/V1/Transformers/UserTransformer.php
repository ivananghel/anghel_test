<?php

namespace App\Api\V1\Transformers;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {

    public function transform(User $user){

		$manager = new Manager();
		$manager->setSerializer(new ArraySerializer());

		$pest_controller = new Item($user->pest_controller, new PestControllerTransformer());
        return [
			'user_id'    		=> (int) $user->id,
			'username'    		=> (string) $user->username,
			'displayName' 		=> (string) $user->displayName,
			'pest_controller'   => $manager->createData($pest_controller)->toArray(),
        ];

    }

}