<?php

use Illuminate\Routing\Router;

$router->controller('auth', 'AuthController', [
    'getRegister' => 'pxcms.user.register',
    'getLogin'    => 'pxcms.user.login',
    'getLogout'   => 'pxcms.user.logout',
]);

$router->controller('password', 'PasswordController', [
    'getEmail' => 'pxcms.user.forgotpassword',
    'getReset' => 'pxcms.user.resetpassword',
]);

$router->group(['prefix' => 'user'], function (Router $router) {
    $router->get('/', ['as' => 'pxcms.user.view', 'uses' => 'AuthController@getProfile']);
});
