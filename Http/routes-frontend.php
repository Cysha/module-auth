<?php

use Illuminate\Routing\Router;

$router->group(['namespace' => 'Auth'], function ($router) {
    // Authentication
    $router->get('login', ['as' => 'pxcms.user.login', 'uses' => 'AuthController@getLogin']);
    $router->post('login', ['uses' => 'AuthController@postLogin']);
    $router->get('logout', ['as' => 'pxcms.user.logout', 'uses' => 'AuthController@getLogout']);

    // Registration
    $router->get('register', ['as' => 'pxcms.user.register', 'uses' => 'AuthController@getRegister']);
    $router->post('register', 'AuthController@postRegister');
    $router->get('registered', ['as' => 'pxcms.user.registered', 'uses' => 'AuthController@getRegistered']);


    $router->controller('password', 'PasswordController', [
        'getEmail' => 'pxcms.user.forgotpassword',
        'getReset' => 'pxcms.user.resetpassword',
    ]);
});

// user control panel
$router->group(['prefix' => 'user', 'namespace' => 'ControlPanel'], function (Router $router) {


    $router->get('/', ['as' => 'pxcms.user.dashboard', 'uses' => 'DashboardController@getIndex']);
});

// profiles
$router->group(['prefix' => 'u/{auth_user}', 'namespace' => 'ControlPanel'], function (Router $router) {


    $router->get('/', ['as' => 'pxcms.user.view', 'uses' => 'DashboardController@getProfile']);
});
