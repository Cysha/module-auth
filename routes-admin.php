<?php


Route::group(array('prefix' => 'admin'), function () {
    $namespace = 'Cysha\Modules\Auth\Controllers\Admin';

    Route::group(array('prefix' => 'users'), function () use ($namespace) {

        Route::get('/', array('as' => 'admin.user.index', 'uses' => $namespace.'\UserController@getDataTableIndex', 'before' => 'permissions'));
    });

});
