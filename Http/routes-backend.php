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
