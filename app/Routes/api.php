<?php

$api = app(\Dingo\Api\Routing\Router::class);

// Routing group for V1
$api->version('v1', ['namespace' => 'App\Api\V1\Controllers'], function($api){

	$api->post('auth/login'					, 'AuthController@login');

	$api->group(['middleware' => ['jwt.auth']], function($api){

	});
});
