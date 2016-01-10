<?php

use Illuminate\Routing\Router;

// Authentication
$router->group(['namespace' => 'Auth'], function ($router) {
    // social has its own login route so dont define it here
    if (!app('modules')->has('Social') || !app('modules')->get('Social')->enabled()) {
        $router->get('login', ['as' => 'pxcms.user.login', 'uses' => 'AuthController@getLogin']);
    }

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
$router->group(['prefix' => 'user', 'namespace' => 'ControlPanel', 'middleware' => 'auth'], function (Router $router) {


    $router->get('permissions', ['as' => 'pxcms.user.permissions', 'uses' => 'PermissionsController@getForm']);

    $router->group(['prefix' => 'avatar'], function(Router $router) {
        $router->post('/', ['uses' => 'AvatarController@postForm']);
        $router->get('/', ['as' => 'pxcms.user.avatar', 'uses' => 'AvatarController@getForm']);
    });

    $router->group(['prefix' => 'notification'], function(Router $router) {
        $router->post('/', ['uses' => 'NotificationController@postForm']);
        $router->get('/', ['as' => 'pxcms.user.notification', 'uses' => 'NotificationController@getForm']);
    });

    $router->group(['prefix' => 'security'], function(Router $router) {
        $router->post('update_password', ['as' => 'pxcms.user.update_password', 'uses' => 'SecurityController@updatePassword']);
        $router->get('/', ['as' => 'pxcms.user.security', 'uses' => 'SecurityController@getForm']);
    });

    $router->group(['prefix' => 'settings'], function(Router $router) {
        $router->post('/', ['uses' => 'SettingsController@postForm']);
        $router->get('/', ['as' => 'pxcms.user.settings', 'uses' => 'SettingsController@getForm']);
    });

    $router->get('/', ['as' => 'pxcms.user.dashboard', 'uses' => 'DashboardController@getIndex']);

});
