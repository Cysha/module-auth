<?php


Route::group(array('prefix' => 'admin'), function () {
$namespace = 'Cysha\Modules\Auth\Controllers\Admin';

    Route::group(array('prefix' => 'users'), function () use ($namespace) {

        Route::get('add',               array('as' => 'admin.user.add',             'uses' => $namespace.'\UserController@getAdd',              'before' => 'permissions'));

        Route::model('user', 'Cysha\Modules\Users\Models\User');
        Route::group(array('prefix' => '{user}'), function () use ($namespace) {
            $namespace .= '\UserEdit';

            Route::group(array('prefix' => 'edit', 'before' => 'permissions:admin.user.edit'), function () use ($namespace) {
                Route::post('/',        array(                                      'uses' => $namespace.'\UserController@postEdit'));
                Route::get('/',         array('as' => 'admin.user.edit',            'uses' => $namespace.'\UserController@getEdit'));
            });

            Route::group(array('prefix' => 'history', 'before' => 'permissions:admin.user.history'), function () use ($namespace) {
                Route::get('/',         array('as' => 'admin.user.history',         'uses' => $namespace.'\HistoryController@getHistory'));
            });

            Route::group(array('prefix' => 'delete', 'before' => 'permissions:admin.user.delete'), function () use ($namespace) {
                Route::post('/',        array('as' => 'admin.user.permissions',     'uses' => $namespace.'\UserController@postDelete'));
            });

            Route::group(array('prefix' => 'permissions', 'before' => 'permissions:admin.user.permissions'), function () use ($namespace) {
                Route::post('/',        array(                                      'uses' => $namespace.'\UserController@postPermissions'));
                Route::get('/',         array('as' => 'admin.user.permissions',     'uses' => $namespace.'\UserController@getPermissions'));
            });

        });

        Route::get('search.json',       array('as' => 'admin.user.ajax',            'uses' => $namespace.'\UserController@getDataTableJson',    'before' => 'permissions:admin.user.index'));
        Route::get('/',                 array('as' => 'admin.user.index',           'uses' => $namespace.'\UserController@getDataTableIndex',   'before' => 'permissions'));
    });

    Route::group(array('prefix' => 'roles'), function () use ($namespace) {
        Route::get('add',               array('as' => 'admin.role.add',             'uses' => $namespace.'\RoleController@getAddRole',          'before' => 'permissions'));
        Route::post('store',            array('as' => 'admin.role.store',           'uses' => $namespace.'\RoleController@postAddRole',         'before' => 'permissions:admin.role.add'));

        Route::model('role', 'Cysha\Modules\Users\Models\Role');
        Route::group(array('prefix' => '{role}'), function () use ($namespace) {
            $namespace .= '\RoleEdit';

            // Route::group(array('prefix' => 'view', 'before' => 'permissions:admin.role.view'), function () use ($namespace) {
            //  Route::post('/',        array(                                      'uses' => $namespace.'\UserController@postEdit'));
            //  Route::get('/',         array('as' => 'admin.role.view',            'uses' => $namespace.'\UserController@getEdit'));
            // });

            Route::group(array('prefix' => 'edit', 'before' => 'permissions:admin.role.edit'), function () use ($namespace) {
                Route::post('/',        array(                                      'uses' => $namespace.'\RoleController@postEdit'));
                Route::get('/',         array('as' => 'admin.role.edit',            'uses' => $namespace.'\RoleController@getEdit'));
            });

            Route::group(array('prefix' => 'user', 'before' => 'permissions:admin.role.edit'), function () use ($namespace) {
                Route::post('/',        array(                                      'uses' => $namespace.'\UserController@postEdit'));
                Route::get('/',         array('as' => 'admin.role.user',            'uses' => $namespace.'\UserController@getEdit'));
            });

            Route::group(array('prefix' => 'permissions', 'before' => 'permissions:admin.role.permissions'), function () use ($namespace) {
                Route::post('/',        array(                                      'uses' => $namespace.'\PermissionsController@postEdit'));
                Route::get('/',         array('as' => 'admin.role.permissions',     'uses' => $namespace.'\PermissionsController@getEdit'));
            });
        });

        Route::get('search.json',       array('as' => 'admin.role.ajax',            'uses' => $namespace.'\RoleController@getDataTableJson',            'before' => 'permissions:admin.role.index'));
        Route::get('/',                 array('as' => 'admin.role.index',           'uses' => $namespace.'\RoleController@getDataTableIndex',           'before' => 'permissions'));
    });

    Route::group(array('prefix' => 'permissions'), function () use ($namespace) {
        Route::get('add',               array('as' => 'admin.permission.add',       'uses' => $namespace.'\PermissionController@getAdd',        'before' => 'permissions'));

        Route::get('/',                 array('as' => 'admin.permission.index',     'uses' => $namespace.'\PermissionController@getIndex',      'before' => 'permissions'));
    });
});
