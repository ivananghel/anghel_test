<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Api\V1\Transformers\WareTransformer;
use App\Jobs\SendEmailRestorePasswordJob;
use App\Models\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use App\Models\Ware;
class WareController extends Controller {

    use Helpers;

	
	 public function ware(){

		$ware = Ware::all();
		return $this->response->collection($ware,new WareTransformer());

    }
    

}