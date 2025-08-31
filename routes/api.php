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

Route::group(['prefix' => "student"], function () {
    Route::get('/{Register_Number}/open-payments', 'OpenPaymentController@History')->name('open-payment.histories');
    // Route::get('/{Register_Number}/bills', 'OpenPaymentController@History')->name('open-payment.history');
    // Route::get('/{Register_Number}/deposit', 'OpenPaymentController@History')->name('open-payment.history');
});