<?php

// Login & out
Route::get('login', array('as' => 'pxcms.user.login', 'uses' => $namespace.'\AuthController@getLogin'));
Route::post('login', $namespace.'\AuthController@postLogin');
Route::get('logout', array('as' => 'pxcms.user.logout', 'uses' => $namespace.'\AuthController@getLogout'));

// Register User
Route::get('register', array('as' => 'pxcms.user.register', 'uses' => $namespace.'\AuthController@getRegister'));
Route::post('register', $namespace.'\AuthController@postRegister');
Route::get('registered', array('as' => 'pxcms.user.registered', 'uses' => $namespace.'\AuthController@getRegistered'));

// Activate User
Route::get('activate/{code}', array('as' => 'user.activate', 'uses' => $namespace.'\AuthController@getActivate'));

// User Control Panel
Route::group(array('prefix' => Config::get('core::routes.paths.user')), function () use ($namespace) {
    $namespace .= '\Module';

    Route::group(['prefix' => 'view'], function () use ($namespace) {
        Route::get('{username}', array('as' => 'pxcms.user.view', 'uses' => $namespace.'\DashboardController@getDashboard'));
    });

    Route::get('dashboard', array('as' => 'pxcms.user.dashboard', 'uses' => $namespace.'\DashboardController@getDashboard'));
    Route::get('/', function () {
        return Redirect::route('pxcms.user.dashboard');
    });

});
