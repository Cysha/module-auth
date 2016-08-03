<?php

use Illuminate\Routing\Router;

// URI: /{backend}/users
$router->group([
    'prefix' => 'users',
    'middleware' => ['hasPermission'],
    'hasPermission' => 'manage@auth_user',
], function (Router $router) {

    $router->group(['prefix' => 'add',  'namespace' => 'User', 'middleware' => 'hasPermission', 'hasPermission' => 'manage.create@auth_user'], function (Router $router) {

        $router->post('/', ['uses' => 'CreateController@postForm']);
        $router->get('/', ['as' => 'admin.user.add', 'uses' => 'CreateController@getForm']);
    });

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

            $router->group(['prefix' => 'permissions'], function (Router $router) {
                $router->get('/', ['as' => 'admin.user.permissions', 'uses' => 'PermissionController@manager']);
            });

            $router->group(['prefix' => 'apikey'], function (Router $router) {
                $router->get('/', ['as' => 'admin.user.apikey', 'uses' => 'ApiKeyController@manager']);
            });
        });

        $router->group(['prefix' => 'view', 'middleware' => 'hasPermission', 'hasPermission' => 'manage.read@auth_user'], function (Router $router) {
            $router->get('/', ['as' => 'admin.user.view', 'uses' => 'InfoController@getIndex']);
        });

        $router->get('/', ['as' => 'admin.user.index', 'uses' => function ($auth_user_id) {
            return redirect(route('admin.user.edit', $auth_user_id));
        }]);
    });

    $router->get('/', ['as' => 'admin.user.manager', 'uses' => 'UserManagerController@userManager']);
});

// URI: /{backend}/roles
$router->group([
    'prefix' => 'roles',
    'middleware' => ['hasPermission'],
    'hasPermission' => 'manage@auth_role',
], function (Router $router) {

    $router->get('add', ['as' => 'admin.role.add', 'uses' => 'RoleManagerController@roleManager']);

    $router->group(['prefix' => '{auth_role_id}', 'namespace' => 'Role'], function (Router $router) {
        $router->group([
            'middleware' => ['hasPermission'],
            'hasPermission' => 'manage.update@auth_role',
        ], function (Router $router) {

            $router->get('edit', ['as' => 'admin.role.edit', 'uses' => 'InfoController@getForm']);

            $router->group(['prefix' => 'users'], function (Router $router) {

                $router->delete('remove/{auth_user_id}', ['as' => 'admin.role.users.remove', 'uses' => 'UserController@deleteRemoveUser']);
                $router->post('add', ['as' => 'admin.role.users.add', 'uses' => 'UserController@postAddUser']);

                $router->get('/', ['as' => 'admin.role.users', 'uses' => 'UserController@manager']);
            });

            $router->group(['prefix' => 'permissions'], function (Router $router) {
                $router->post('/', ['as' => 'admin.role.permissions', 'uses' => 'PermissionController@postForm']);
                $router->get('/', ['as' => 'admin.role.permissions', 'uses' => 'PermissionController@getForm']);
            });
        });

        $router->get('/', ['as' => 'admin.role.index', 'uses' => 'InfoController@redirect']);
    });

    $router->post('/', ['uses' => 'RoleManagerController@roleManager']);
    $router->get('/',  ['as' => 'admin.role.manager', 'uses' => 'RoleManagerController@roleManager']);
});

// URI: /{backend}/permissions
$router->group([
    'prefix' => 'permissions',
    'middleware' => ['hasPermission'],
    'hasPermission' => 'manage@auth_permission',
], function (Router $router) {

    $router->get('add', ['as' => 'admin.permission.add', 'uses' => 'PermissionManagerController@permissionManager']);

    $router->post('/', ['uses' => 'PermissionManagerController@permissionManager']);
    $router->get('/', ['as' => 'admin.permission.manager', 'uses' => 'PermissionManagerController@permissionManager']);
});

// URI: /{backend}/api
$router->group([
    'prefix' => 'api',
    'middleware' => 'hasPermission',
    'hasPermission' => 'api@auth_config',
], function (Router $router) {

    $router->group(['prefix' => 'create', 'namespace' => 'Api'], function (Router $router) {
        $router->post('/', ['uses' => 'CreateController@postForm']);
        $router->get('/', ['as' => 'admin.apikey.create', 'uses' => 'CreateController@getForm']);
    });

    $router->group(['prefix' => '{auth_apikey_id}', 'namespace' => 'Api'], function (Router $router) {

        $router->group(['prefix' => 'remove'], function (Router $router) {
            $router->delete('/', ['as' => 'admin.apikey.remove', 'uses' => 'RemoveController@deleteApiKey']);
        });

    });

    $router->get('/', ['as' => 'admin.apikey.manager', 'uses' => 'ApiManagerController@manager']);
});

// URI: /{backend}/config
$router->group([
    'prefix' => 'config',
    'namespace' => 'Config',
], function (Router $router) {

    $router->get('users', ['as' => 'admin.config.users', 'uses' => 'UsersController@getIndex', 'middleware' => 'hasPermission', 'hasPermission' => 'users@auth_config']);

    $router->get('authentication', ['as' => 'admin.config.authentication', 'uses' => 'AuthController@getIndex', 'middleware' => 'hasPermission', 'hasPermission' => 'authentication@auth_config']);

    $router->get('api', ['as' => 'admin.config.api', 'uses' => 'ApiController@getIndex', 'middleware' => 'hasPermission', 'hasPermission' => 'api@auth_config']);
});
