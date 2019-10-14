<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('test', 'TapCardController@test');

// /api/tap
Route::group(['prefix' => 'tap'], function () {
    // /api/tap/{ipAddress}/{rfid}
    Route::get('{ipAddress}/{rfid}', 'TapCardController@activate');
});

// /api/live/{email}
Route::post('live/{email}', 'LiveHostController@index');

// /api/machines
Route::group(['prefix' => 'machines'], function() {
    // /api/machines/create
    Route::post('create', 'MachinesController@store');

    // /api/machines/{machineId}/update
    Route::post('{machineId}/update', 'MachinesController@update');

    // /api/machines/{machineId}/delete-machine
    Route::post('{machineId}/delete-machine', 'MachinesController@destroy');
});


// /api/search
Route::group(['prefix' => 'search', 'middleware' => 'auth:api'], function () {
    // /api/search/products
    Route::get('products', 'ProductsController@index');
});

// /api/account
Route::group(['prefix' => 'account', 'middleware' => 'auth:api'], function() {
    // /api/account/{id}|self/update-profile
    Route::post('{id}/update-profile', 'AccountsController@updateProfile');

    // /api/account/{id}|self/update-email
    Route::post('{id}/update-email', 'AccountsController@updateEmail');

    // /api/account/{id}|self/update-password
    Route::post('{id}/update-password', 'AccountsController@updatePassword');

    // /api/account/{id}|self/get-account-info
    Route::get('{id}/get-account-info', 'AccountsController@getAccountInfo');
});

// /api/users
Route::group(['prefix' => 'users', 'middleware' => 'auth:api'], function() {
    // /api/users/{clientId}|self
    Route::post('{clientId}/create', 'UsersController@create')->middleware('self:clientId');

    // /api/users/{userId}/assign-role
    Route::post('{userId}/assign-role', 'UsersController@assignRole');
});

Route::group(['middleware' => 'auth:api'], function () {
    // /api/daily-sales
    Route::group(['prefix' => 'daily-sales'], function() {
        // /api/daily-sales/{date}/breakdown
        Route::get('{date}/breakdown', 'DailySalesController@breakdown');

        // /api/daily-sales/{year}/{month}
        Route::get('{year}/{month}', 'DailySalesController@index');
    });
});

// /api/summary
Route::group(['prefix' => 'summary'], function() {
    // /api/summary/{date}/rfid-summary
    Route::get('{date}/rfid-summary', 'DailySalesController@rfidTransactionSummary');

    // /api/summary/{date}/services-summary
    Route::get('{date}/services-summary', 'DailySalesController@servicesTransactionSummary');
});

// /api/all
Route::group(['prefix' => 'all', 'middleware' => 'auth:api'], function() {
    // /api/all/service-prices
    Route::get('service-prices', 'ServicePricesController@index');
});

// oauth
Route::group(['prefix' => 'oauth', 'middleware' => 'auth:api'], function() {
    // oauth/check
    Route::get('check', 'OAuthController@check');

    // oauth/logout
    Route::post('logout', 'OAuthController@logout');
});

Route::group(['prefix' => 'oauth'], function() {
    Route::post('login', 'OAuthController@login');
});


Route::any('{any}', function() {
    return response()->json(['message' => 'Resource not found'], 404);
})->where('any', '.*');
