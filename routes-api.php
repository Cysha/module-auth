<?php

Route::api(['version' => 'v1', 'prefix' => 'api', 'protected' => true], function() {

    Route::get('user', function() {
        return ['authed_as' => Auth::user()->username];
    });

});
