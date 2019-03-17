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

Route::middleware(['auth:api'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('reset-password', 'AuthController@resetPassword');
    Route::post('locale', 'AuthController@changeLocale');
    Route::post('check-unique', 'AuthController@checkUnique');
});
Route::group(['prefix' => 'personal'], function () {
    Route::get('user', 'PersonalPageController@user');
    Route::post('logout', 'PersonalPageController@logout');
    Route::get('refresh', 'PersonalPageController@refresh');
    Route::post('change-password', 'PersonalPageController@changePassword');
});
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')
    ->name('verification.verify');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
    ->name('password.email');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')
    ->name('password.update');
Route::get('email/resend', 'Auth\VerificationController@resend')
    ->name('verification.resend');
Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')
    ->name('password.request');
Route::post('/recaptcha', 'AuthController@verifyCaptcha');