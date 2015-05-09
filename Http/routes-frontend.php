<?php


Route::group(['namespace' => $namespace.'\Frontend'], function () {

    Route::controller('auth', 'AuthController', [
        'getRegister' => 'pxcms.user.register',
        'getLogin'    => 'pxcms.user.login',
        'getLogout'   => 'pxcms.user.logout',
    ]);


    Route::controllers([
        'auth' => 'AuthController',
        'password' => 'PasswordController',
    ]);


});
