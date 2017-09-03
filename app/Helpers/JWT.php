<?php

namespace App\Helpers;

use App\Exceptions\AuthenticationException;
use App\Exceptions\PermissionException;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use Tymon\JWTAuth\Facades\JWTAuth;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

/**
 * Class JWT
 * @package App\Helpers
 */
final class JWT extends Helper {

    /**
     * @var User|null
     */
    private static $user = null;

    /**
     * @param string $email
     * @param string $password
     * @return string
     */
    public static function login($email, $password){
        $token = JWTAuth::attempt([
            'email'     => $email,
            'password'  => $password,
            'status'    => User::USER_STATUS_ACTIVE,
        ]);

        if(false === $token){
            abort(401, 'Invalid Credentials');
        }

        $user = Auth::user();

        if($user->hasRole('user') ||  $user->hasRole('admin')){
            $data = [
                'user_id' => $user->id,
                'full_name' => $user->first_name.' '.$user->last_name,
            ];

            $token = JWTAuth::attempt([
                'email'     => $email,
                'password'  => $password,
                'status'    => User::USER_STATUS_ACTIVE,
            ], $data);

            if(false === $token){
                abort(401, 'Invalid Credentials');
            }

            return $token;
        }

        return $token;
    }

        /**
     * @param string $email
     * @param string $password
     * @return string
     */
    public static function loginfacebook($user){

        $token = JWTAuth::fromUser($user);

        if(!$token){
            throw new AuthenticationException('Invalid Credentials');
        }

        return $token;
    }


    /**
     * @param string $token
     * @return string
     * @throws AuthenticationException
     * @throws PermissionException
     */
/*    public static function loginWithFacebook($token){

        $fb = app(LaravelFacebookSdk::class);
        $response = $fb->get('/me?fields=id,name,email', $token);
        $fb_data = $response->getGraphUser()->all();

        $user = User::where(['email'=> $fb_data['email']])
			->orWhere(['facebook_id'=> $fb_data['id']])
			->first();
        if(null === $user){
            return null;
        }

        $token = JWTAuth::fromUser($user);
        if(!$token){
            throw new AuthenticationException('Invalid Credentials');
        }

        Auth::setUser($user);

        return $token;
    }*/

	/**
	 * @param string $token
	 * @return array
	 * @throws AuthenticationException
	 */
/*    public static function registerWithFacebook($token){

        $fb 		= app(LaravelFacebookSdk::class);
        $response 	= $fb->get('/me?fields=id,first_name,last_name,email,birthday', $token);
        $fb_data 	= $response->getGraphUser()->all();

        $user = User::where([
            ['email', '=', $fb_data['email']],
        ])->first();

        if(null === $user){

			// If not exists, create one
			$user = DB::transaction(function() use($fb_data) {

				$user = new User([
					'first_name' => isset($fb_data['first_name']) ? $fb_data['first_name'] : '',
					'last_name' => isset($fb_data['last_name']) ? $fb_data['last_name'] : '',
					'email' => $fb_data['email'],
					'password' => Hash::make('Hh+1uIJfyB?o%"H'),
					'facebook_id' => $fb_data['id'],
				]);

				$user->status = User::USER_STATUS_ACTIVE;
				$user->save();

				$user->reseller()->create([]);
				$user->attachRole(Role::ROLE_RESELLER);

				return $user;
			});

			$manager = new Manager();
			$manager->setSerializer(new ArraySerializer());
			if(null !== $user->reseller){
				$reseller = new Item($user->reseller, new AgentTransformer());
			}else {
				abort(401, 'Invalid user role');
			}
			return [
				'token' => $token,
				'data' => $manager->createData($reseller)->toArray(),
			];
        }
		$token = JWTAuth::fromUser($user);

		if(!$token){
			throw new AuthenticationException('Invalid Credentials');
		}

		Auth::setUser($user);

		return $token;

    }*/

    /**
     * @return User|null
     */
    public static function user(){
        return Auth::user();
    }

}