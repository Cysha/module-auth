<?php


Route::group(['prefix' => 'admin', 'namespace' => $namespace.'\Admin'], function () {

    Route::group(['prefix' => 'users'], function () {

        Route::get('add', ['as' => 'admin.user.add', 'uses' => 'UserController@getAdd', 'before' => 'permissions']);

        Route::model('user', Config::get('auth.model'));
        Route::group(['prefix' => '{user}', 'namespace' => 'UserEdit'], function () {
            Route::group(['prefix' => 'edit', 'before' => 'permissions:admin.user.edit'], function () {
                Route::post('/', ['uses' => 'UserController@postEdit']);
                Route::get('/', ['as' => 'admin.user.edit', 'uses' => 'UserController@getEdit']);
            });

            Route::group(['prefix' => 'history', 'before' => 'permissions:admin.user.history'], function () {
                Route::get('/', ['as' => 'admin.user.history', 'uses' => 'HistoryController@getHistory']);
            });

            Route::group(['prefix' => 'password', 'before' => 'permissions:admin.user.password'], function () {
                Route::get('/', ['as' => 'admin.user.password', 'uses' => 'PasswordController@getForm']);
                Route::post('/', ['uses' => 'PasswordController@postForm']);
            });

            Route::group(['prefix' => 'delete', 'before' => 'permissions:admin.user.delete'], function () {
                Route::post('/', ['as' => 'admin.user.permissions', 'uses' => 'UserController@postDelete']);
            });

            Route::group(['prefix' => 'permissions', 'before' => 'permissions:admin.user.permissions'], function () {
                Route::post('/', ['uses' => 'UserController@postPermissions']);
                Route::get('/', ['as' => 'admin.user.permissions', 'uses' => 'UserController@getPermissions']);
            });

        });

        Route::get('search.json', ['as' => 'admin.user.ajax', 'uses' => 'UserController@getDataTableJson', 'before' => 'permissions:admin.user.index']);
        Route::get('/', ['as' => 'admin.user.index', 'uses' => 'UserController@getDataTableIndex', 'before' => 'permissions']);
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('add', ['as' => 'admin.role.add', 'uses' => 'RoleController@getAddRole', 'before' => 'permissions']);
        Route::post('store', ['as' => 'admin.role.store', 'uses' => 'RoleController@postAddRole', 'before' => 'permissions:admin.role.add']);

        Route::model('role', 'Cysha\Modules\Auth\Models\Role');
        Route::group(['prefix' => '{role}', 'namespace' => 'RoleEdit'], function () {

            // Route::group(['prefix' => 'view', 'before' => 'permissions:admin.role.view'], function () {
            //  Route::post('/', ['uses' => 'UserController@postEdit']);
            //  Route::get('/', ['as' => 'admin.role.view', 'uses' => 'UserController@getEdit']);
            // });

            Route::group(['prefix' => 'edit', 'before' => 'permissions:admin.role.edit'], function () {
                Route::post('/', ['uses' => 'RoleController@postEdit']);
                Route::get('/', ['as' => 'admin.role.edit', 'uses' => 'RoleController@getEdit']);
            });

            Route::group(['prefix' => 'user', 'before' => 'permissions:admin.role.edit'], function () {
                Route::post('/', ['uses' => 'UserController@postEdit']);
                Route::get('/', ['as' => 'admin.role.user', 'uses' => 'UserController@getEdit']);
            });

            Route::group(['prefix' => 'permissions', 'before' => 'permissions:admin.role.permissions'], function () {
                Route::post('/', ['uses' => 'PermissionsController@postEdit']);
                Route::get('/', ['as' => 'admin.role.permissions', 'uses' => 'PermissionsController@getEdit']);
            });
        });

        Route::get('search.json', ['as' => 'admin.role.ajax', 'uses' => 'RoleController@getDataTableJson', 'before' => 'permissions:admin.role.index']);
        Route::get('/', ['as' => 'admin.role.index', 'uses' => 'RoleController@getDataTableIndex', 'before' => 'permissions']);
    });

    Route::group(['prefix' => 'permissions'], function () {
        Route::get('add', ['as' => 'admin.permission.add', 'uses' => 'PermissionController@getAdd', 'before' => 'permissions']);

        Route::get('search.json', ['as' => 'admin.permission.ajax', 'uses' => 'PermissionController@getDataTableJson', 'before' => 'permissions:admin.permission.index']);
        Route::get('/', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@getDataTableIndex', 'before' => 'permissions']);
    });
});
