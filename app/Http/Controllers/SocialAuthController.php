<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;
use App\Models\User;
use Auth;

class SocialAuthController extends Controller
{
	public function redirect()
	{
		return Socialite::driver('facebook')
		->scopes(['email'])->redirect();
	}   

	public function callback()
	{
		try {
			$Socialuser = Socialite::driver('facebook')->user();
		} catch (Exception $e) {
			return redirect('/');
		}


		$user = User::where('facebook_id' , $Socialuser->id)->first();
	
		if(!$user){

			$user = User::create([
				'facebook_id'	=> $Socialuser->id,
				'email'			=> $Socialuser->email,
				'first_name'	=> $Socialuser->name,
				'last_name'		=> $Socialuser->name,
				'status'		=> 1
				]);
		}

		auth()->login($user);
		return redirect()->to('/articole');


	}
}