<?php


Route::group(['prefix' => 'users'], function () {
    Route::get('add', ['as' => 'admin.user.add', 'uses' => 'UserManagerController@userManager']);

    Route::post('/', ['uses' => 'UserManagerController@userManager']);
    Route::get('/', ['as' => 'admin.user.manager', 'uses' => 'UserManagerController@userManager']);
});

Route::group(['prefix' => 'roles'], function () {
    Route::get('add', ['as' => 'admin.role.add', 'uses' => 'RoleManagerController@roleManager']);

    Route::post('/', ['uses' => 'RoleManagerController@roleManager']);
    Route::get('/', ['as' => 'admin.role.manager', 'uses' => 'RoleManagerController@roleManager']);
});

Route::group(['prefix' => 'permission'], function () {
    Route::get('add', ['as' => 'admin.permission.add', 'uses' => 'PermissionManagerController@permissionManager']);

    Route::post('/', ['uses' => 'PermissionManagerController@permissionManager']);
    Route::get('/', ['as' => 'admin.permission.manager', 'uses' => 'PermissionManagerController@permissionManager']);
});
