<?php

use Illuminate\Routing\Router;

// URI: /{backend}/users
$router->group([
    'prefix'        => 'users',
    'middleware'    => 'hasPermission',
    'hasPermission' => 'manage@auth_user'
], function (Router $router) {

    $router->get('add', ['as' => 'admin.user.add', 'uses' => 'UserManagerController@userManager', 'middleware' => 'hasPermission', 'hasPermission' => 'create@auth_user']);

    $router->group(['prefix' => '{auth_user_id}', 'namespace' => 'User'], function (Router $router) {

        $router->group(['middleware' => 'hasPermission', 'hasPermission' => 'manage.update@auth_user'], function (Router $router) {
            $router->group(['prefix' => 'password'], function (Router $router) {
                $router->post('/', ['uses' => 'PasswordController@postForm']);
                $router->get('/', ['as' => 'admin.user.password', 'uses' => 'PasswordController@getForm']);
            });

            $router->group(['prefix' => 'avatar'], function (Router $router) {
                $router->post('/', ['uses' => 'AvatarController@postForm']);
                $router->get('/', ['as' => 'admin.user.avatar', 'uses' => 'AvatarController@getForm']);
            });

            $router->group(['prefix' => 'roles'], function (Router $router) {
                $router->post('/', ['uses' => 'RoleController@postForm']);
                $router->get('/', ['as' => 'admin.user.role', 'uses' => 'RoleController@getForm']);
            });

            $router->group(['prefix' => 'edit'], function (Router $router) {
                $router->post('/', ['uses' => 'InfoController@postForm']);
                $router->get('/', ['as' => 'admin.user.edit', 'uses' => 'InfoController@getForm']);
            });
        });

        $router->group(['prefix' => 'view', 'middleware' => 'hasPermission', 'hasPermission' => 'manage.read@auth_user'], function (Router $router) {
            $router->get('/', ['as' => 'admin.user.view', 'uses' => 'InfoController@getIndex']);
        });

        $router->get('/', ['as' => 'admin.user.index', 'uses' => 'InfoController@redirect']);
    });

    $router->post('/', ['uses' => 'UserManagerController@userManager']);
    $router->get('/', ['as' => 'admin.user.manager', 'uses' => 'UserManagerController@userManager']);
});

// URI: /{backend}/roles
$router->group([
    'prefix'        => 'roles',
    'middleware'    => ['hasPermission'],
    'hasPermission' => 'manage@auth_role'
], function (Router $router) {

    $router->get('add', ['as' => 'admin.role.add', 'uses' => 'RoleManagerController@roleManager']);

    $router->post('/', ['uses' => 'RoleManagerController@roleManager']);
    $router->get('/', ['as' => 'admin.role.manager', 'uses' => 'RoleManagerController@roleManager']);
});

// URI: /{backend}/permissions
$router->group([
    'prefix'        => 'permissions',
    'middleware'    => ['hasPermission'],
    'hasPermission' => 'manage@auth_permission'
], function (Router $router) {

    $router->get('add', ['as' => 'admin.permission.add', 'uses' => 'PermissionManagerController@permissionManager']);

    $router->post('/', ['uses' => 'PermissionManagerController@permissionManager']);
    $router->get('/', ['as' => 'admin.permission.manager', 'uses' => 'PermissionManagerController@permissionManager']);
});
