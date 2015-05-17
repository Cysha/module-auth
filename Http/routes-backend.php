<?php


Route::group([
    'prefix'        => 'users',
    'middleware'    => 'hasPermission',
    'hasPermission' => 'manage@auth_user'
], function () {
    Route::get('add', ['as' => 'admin.user.add', 'uses' => 'UserManagerController@userManager']);

    Route::post('/', ['uses' => 'UserManagerController@userManager']);
    Route::get('/', ['as' => 'admin.user.manager', 'uses' => 'UserManagerController@userManager']);
});

Route::group([
    'prefix'        => 'roles',
    'middleware'    => 'hasPermission',
    'hasPermission' => 'manage@auth_role'
], function () {
    Route::get('add', ['as' => 'admin.role.add', 'uses' => 'RoleManagerController@roleManager']);

    Route::post('/', ['uses' => 'RoleManagerController@roleManager']);
    Route::get('/', ['as' => 'admin.role.manager', 'uses' => 'RoleManagerController@roleManager']);
});

Route::group([
    'prefix'        => 'permissions',
    'middleware'    => 'hasPermission',
    'hasPermission' => 'manage@auth_permission'
], function () {
    Route::get('add', ['as' => 'admin.permission.add', 'uses' => 'PermissionManagerController@permissionManager']);

    Route::post('/', ['uses' => 'PermissionManagerController@permissionManager']);
    Route::get('/', ['as' => 'admin.permission.manager', 'uses' => 'PermissionManagerController@permissionManager']);
});
