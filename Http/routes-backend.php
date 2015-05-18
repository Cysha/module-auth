<?php

use Illuminate\Routing\Router;

$router->group([
    'prefix'        => 'users',
    'middleware'    => 'hasPermission',
    'hasPermission' => 'manage@auth_user'
], function (Router $router) {

    $router->get('add', ['as' => 'admin.user.add', 'uses' => 'UserManagerController@userManager']);

    $router->post('/', ['uses' => 'UserManagerController@userManager']);
    $router->get('/', ['as' => 'admin.user.manager', 'uses' => 'UserManagerController@userManager']);
});

$router->group([
    'prefix'        => 'roles',
    'middleware'    => ['hasPermission'],
    'hasPermission' => 'manage@auth_role'
], function (Router $router) {

    $router->get('add', ['as' => 'admin.role.add', 'uses' => 'RoleManagerController@roleManager']);

    $router->post('/', ['uses' => 'RoleManagerController@roleManager']);
    $router->get('/', ['as' => 'admin.role.manager', 'uses' => 'RoleManagerController@roleManager']);
});

$router->group([
    'prefix'        => 'permissions',
    'middleware'    => ['hasPermission'],
    'hasPermission' => 'manage@auth_permission'
], function (Router $router) {

    $router->get('add', ['as' => 'admin.permission.add', 'uses' => 'PermissionManagerController@permissionManager']);

    $router->post('/', ['uses' => 'PermissionManagerController@permissionManager']);
    $router->get('/', ['as' => 'admin.permission.manager', 'uses' => 'PermissionManagerController@permissionManager']);
});
