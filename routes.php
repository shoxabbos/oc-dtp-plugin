<?php


// public methods
Route::group([
    'prefix' => 'api', 
    'middleware' => [
    	'Itmaker\DtpApp\Middlewares\Logger',
    	'Itmaker\DtpApp\Middlewares\LanguageDetector',
    ]
], function() {
    // auth
    Route::post('auth/signin', 'Itmaker\DtpApp\Api\Auth@signin');
    Route::post('auth/signup', 'Itmaker\DtpApp\Api\Auth@signup');
    
    Route::post('auth/refresh-token', 'Itmaker\DtpApp\Api\Auth@refresh');
    Route::post('auth/invalidate-token', 'Itmaker\DtpApp\Api\Auth@invalidate');

    Route::post('auth/restore-password', 'Itmaker\DtpApp\Api\Auth@restorePassword'); // step 1
    Route::post('auth/reset-password', 'Itmaker\DtpApp\Api\Auth@resetPassword'); // step 2

    // Helper
	Route::get('helper/tariffs', 'Itmaker\DtpApp\Api\Helper@tariffs');
	Route::get('helper/services', 'Itmaker\DtpApp\Api\Helper@services');
});
 

Route::group([
    'prefix' => 'api',
    'middleware' => [
    	'Itmaker\DtpApp\Middlewares\Logger',
    	'\Tymon\JWTAuth\Middleware\GetUserFromToken',
        'Itmaker\DtpApp\Middlewares\LanguageDetector',
    ]
], function() {
	// private methods of user
    Route::get('user/get', 'Itmaker\DtpApp\Api\User@get');
    Route::post('user/update', 'Itmaker\DtpApp\Api\User@update');


    // private methods of call
    Route::post('calls', 'Itmaker\DtpApp\Api\Calls@create');
});






















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
	Route::post('/cancel-call', 'itmaker\dtpapp\api\Api@cancelCall');
	Route::get('/calls/history/{page}', 'itmaker\dtpapp\api\Api@callsHistory');
	Route::get('/calls/get/{id}', 'itmaker\dtpapp\api\Api@getCall');
	Route::get('last-call', 'itmaker\dtpapp\api\Api@getLastCall');
	Route::post('/call/completed', 'itmaker\dtpapp\api\Api@callCompleted');
	Route::post('create-rate', 'itmaker\dtpapp\api\Rates@createRate');
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
	Route::post('/locations/set', 'itmaker\dtpapp\api\Locations@set');
	Route::get('/locations/get/{id}', 'itmaker\dtpapp\api\Locations@get');
	Route::post('/balance/replenishment', 'itmaker\dtpapp\api\Payment@renderUrl');
});