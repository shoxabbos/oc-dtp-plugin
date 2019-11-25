<?php

// index route for clients app
Route::group([
	'prefix' => 'api', 
	'middleware' => '\Itmaker\DtpApp\Classes\LocaleMiddleware'
], function() {
	Route::get('index', 'itmaker\dtpapp\controllers\Api@index');
	//Route::get('get-slides', 'itmaker\dtpapp\controllers\Api@getSlides');
});

//login and register routes for all users
Route::group([
	'prefix' => '/api',
	'middleware' => '\Itmaker\DtpApp\Classes\LocaleMiddleware'
], function(){
	Route::post('/client/register', 'itmaker\dtpapp\controllers\UsersApi@registerClient');
    Route::post('/client/login', 'itmaker\dtpapp\controllers\UsersApi@loginClient');
    Route::post('/specialist/login/', 'itmaker\dtpapp\controllers\UsersApi@loginSpecialist');
    Route::post('/master/register', 'itmaker\dtpapp\controllers\UsersApi@registerMasters');
    Route::post('/master/login', 'itmaker\dtpapp\controllers\UsersApi@loginMaster');
	Route::get('/get-user-groups', 'itmaker\dtpapp\controllers\UsersApi@getGroups');
});

Route::group([
	'prefix' => 'api/client',
	'middleware' => ['\Tymon\JWTAuth\Middleware\GetUserFromToken', '\Itmaker\DtpApp\Classes\LocaleMiddleware']
], function() {
	Route::get('/get-user', 'itmaker\dtpapp\controllers\UsersApi@getUser');
	Route::post('/update-user', 'itmaker\dtpapp\controllers\UsersApi@updateUser');
	Route::post('/calling', 'itmaker\dtpapp\controllers\Api@calling');
	Route::get('/calls/history/{page}', 'itmaker\dtpapp\controllers\Api@callsHistory');
	Route::get('/calls/get/{id}', 'itmaker\dtpapp\controllers\Api@getCall');
	Route::get('last-call', 'itmaker\dtpapp\controllers\Api@getLastCall');
});

Route::group([
	'prefix' => 'api/employe',
	'middleware' => ['\Tymon\JWTAuth\Middleware\GetUserFromToken', '\Itmaker\DtpApp\Classes\LocaleMiddleware']
], function() {
	Route::get('/get-user', 'itmaker\dtpapp\controllers\Api@getUser');
	Route::post('/update-user', 'itmaker\dtpapp\controllers\Api@updateUser');
	Route::get('/calls/active/{page}', 'itmaker\dtpapp\controllers\Api@activeCalls');
	Route::get('/calls/spents', 'itmaker\dtpapp\controllers\Api@mySpents');
	Route::post('/call/employed', 'itmaker\dtpapp\controllers\Api@callEmployed');
	Route::post('/call/complated', 'itmaker\dtpapp\controllers\Api@callComplated');
	Route::post('/locations/set', 'itmaker\dtpapp\api\Locations@set');
	Route::get('/locations/get/{id}', 'itmaker\dtpapp\api\Locations@get');
});