<?php

Route::controller('auth', 'AuthController', [
    'getRegister' => 'pxcms.user.register',
    'getLogin'    => 'pxcms.user.login',
    'getLogout'   => 'pxcms.user.logout',
]);

Route::controller('password', 'PasswordController', [
    'getEmail' => 'pxcms.user.forgotpassword',
    'getReset' => 'pxcms.user.resetpassword',
]);
