<?php

return [
    'paths' => [
        'redirect_login'    => 'pxcms.user.dashboard',
        'redirect_logout'   => 'pxcms.pages.index',
        'redirect_register' => 'pxcms.user.registered',
    ],
    'users' => [
        'require_activating' => 'false',
        'force_screenname' => 'NULL',
        'login' => [
            'throttlingEnabled' => 'false',
            'lockoutTime' => 60,
            'maxLoginAttempts' => 5,
        ]
    ],
    'recaptcha' => [
        'login_form' => 'false',
        'register_form' => 'false',
    ],
    'roles' => [
        'admin_group' => 1,
        'user_group' => 3,
        'guest_group' => 4,
    ],
];
