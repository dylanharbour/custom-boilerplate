<?php

use \App\Http\Middleware\MobileNumberVerifiedMiddleware;

/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', 'FrontendController@index')->name('index');
Route::get('macros', 'FrontendController@macros')->name('macros');

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 */
Route::group(['middleware' => 'auth'], function () {

    /*
     * These are the endpoints that allow users to verify their mobile numbers. No other endpoints should be put here.
     */
    Route::group(['namespace' => 'Auth', 'as' => 'confirm.'], function () {
        Route::get('mobile/verify')
            ->uses('MobileNumberVerificationController@show')
            ->name('mobile.show');

        Route::post('mobile/verify')
            ->uses('MobileNumberVerificationController@confirm')
            ->name('mobile.verify');

        Route::post('mobile/resend')
            ->uses('MobileNumberVerificationController@sendConfirmationSms')
            ->name('mobile.resend');
    });

    Route::group(
        [
            'namespace' => 'User',
            'as' => 'user.',
            'middleware' => MobileNumberVerifiedMiddleware::class,
        ], function () {

        /*
         * User Dashboard Specific
         */
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        /*
         * User Account Specific
         */
        Route::get('account', 'AccountController@index')->name('account');

        /*
         * User Profile Specific
         */
        Route::patch('profile/update', 'ProfileController@update')->name('profile.update');
        });
});
