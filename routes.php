<?php

// index route for clients app
Route::group([
	'prefix' => 'api', 
	'middleware' => '\Itmaker\DtpApp\Classes\LocaleMiddleware'
], function() {
	Route::get('index', 'itmaker\dtpapp\api\Api@index');
	//Route::get('get-slides', 'itmaker\dtpapp\api\Api@getSlides');
});

//login and register routes for all users
Route::group([
	'prefix' => '/api',
	'middleware' => '\Itmaker\DtpApp\Classes\LocaleMiddleware'
], function(){
	Route::post('/client/register', 'itmaker\dtpapp\api\Users@registerClient');
    Route::post('/client/login', 'itmaker\dtpapp\api\Users@loginClient');
    Route::post('/specialist/login/', 'itmaker\dtpapp\api\Users@loginSpecialist');
    Route::post('/master/register', 'itmaker\dtpapp\api\Users@registerMasters');
    Route::post('/master/login', 'itmaker\dtpapp\api\Users@loginMaster');
	Route::get('/get-master-groups', 'itmaker\dtpapp\api\Users@getGroups');
});

Route::group([
	'prefix' => 'api/client',
	'middleware' => ['\Tymon\JWTAuth\Middleware\GetUserFromToken', '\Itmaker\DtpApp\Classes\LocaleMiddleware']
], function() {
	Route::get('/get-user', 'itmaker\dtpapp\api\Users@getUser');
	Route::post('/update-user', 'itmaker\dtpapp\api\Users@updateUser');
	Route::post('/calling', 'itmaker\dtpapp\api\Api@calling');
	Route::get('/calls/history/{page}', 'itmaker\dtpapp\api\Api@callsHistory');
	Route::get('/calls/get/{id}', 'itmaker\dtpapp\api\Api@getCall');
	Route::get('last-call', 'itmaker\dtpapp\api\Api@getLastCall');
});

Route::group([
	'prefix' => 'api/employe',
	'middleware' => ['\Tymon\JWTAuth\Middleware\GetUserFromToken', '\Itmaker\DtpApp\Classes\LocaleMiddleware']
], function() {
	Route::get('/get-user', 'itmaker\dtpapp\api\Api@getUser');
	Route::post('/update-user', 'itmaker\dtpapp\api\Users@updateUser');
	Route::get('/calls/active/{page?}', 'itmaker\dtpapp\api\Api@activeCalls');
	Route::get('/calls/spents/{status}', 'itmaker\dtpapp\api\Api@mySpents');
    Route::get('/call/get/{id}', 'itmaker\dtpapp\api\Api@getCallToId');
    Route::get('/call/employed/{id}', 'itmaker\dtpapp\api\Api@callEmployed');
    Route::get('/call/arrived/{id}', 'itmaker\dtpapp\api\Api@callArrived');
	Route::post('/call/completed', 'itmaker\dtpapp\api\Api@callCompleted');
	Route::post('/locations/set', 'itmaker\dtpapp\api\Locations@set');
	Route::get('/locations/get/{id}', 'itmaker\dtpapp\api\Locations@get');
	Route::post('/balance/replenishment', 'itmaker\dtpapp\api\Payment@renderUrl');
});