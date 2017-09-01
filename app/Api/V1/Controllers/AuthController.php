<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Transformers\UserTransformer;
use App\Helpers\JWT;
use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use Tymon\JWTAuth\Facades\JWTAuth;
use Hash;
class AuthController extends Controller {

    use Helpers;

    /**
     * Sign into the system
     *
     * @route POST /api/auth/login
     *
     * @param Request $request
     * @return array
     */
 public function login(Request $request){
        // Validation
        $this->validate($request, [
            'email'         => 'required|string|exists:users,email',
            'password'      => 'required|string',
        ]);

        // Authentication
        $token  = JWT::login($request->email, $request->password);

        return [
            'data' => ['token' => $token],
        ];

    }


}