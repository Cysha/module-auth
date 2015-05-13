<?php


Route::group(['prefix' => 'users'], function () {


    Route::get('add', ['as' => 'admin.user.add', 'uses' => 'UserManagerController@userManager']);

    Route::post('/', ['uses' => 'UserManagerController@userManager']);
    Route::get('/', ['as' => 'admin.user.manager', 'uses' => 'UserManagerController@userManager']);
});
