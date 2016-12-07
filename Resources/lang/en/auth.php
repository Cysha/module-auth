<?php

return [
    'permissions.authenticated' => 'You need to be authenticated to get there.',
    'permissions.unauthorized' => 'You do not have the neccesary permissions for that. (:permission@:resource::resource_id)',

    'user.deleted' => 'Email / Password combination you provided cannot be found, Please try again.',
    'user.notfound' => 'Email / Password combination you provided cannot be found, Please try again.',
    'user.incorrect_credentials' => 'Email / Password combination you provided cannot be found, Please try again.',

    'user.logged_out_successfully' => 'You have successfully logged out.',

    'user.secure_password' => 'Atleast 1 Digit, 1 Upper and Lower case, 1 Special Character and atleast 8 characters in length',
    'user.expire_passwords' => 'Force passwords to expire after a set amount of time. Users with expired passwords will be forced to change it upon next login.',
    'user.password_age' => 'How old can the passwords get before requiring reset.',
    'user.expired_password' => 'Either your password has expired or an Administrator has forcibly expired it, to continue you will need to set a new one.',

    'user.password_changed' => 'Your password has been successfully changed.',

    // 2fa
    'user.2fa_enabled' => 'This account has 2 Factor Authentication enabled. Please input the code from your device to continue.',
    'user.2fa_verified' => '2 Factor Authentication verified successfully. Please ensure this is used from now on.',
    'user.2fa_bypass' => 'When logging into a 2 Factor Authenticated account, you must validate yourself before trying to continue. You have been logged out.',
    'user.2fa_disabled' => '2 Factor Authentication has been disabled, remember to remove this account from your device (:site_name::user_email).',
    'user.2fa_thanks' => 'Thanks for helping us to help you keep your account safe!',
    'user.2fa_code_error' => 'Code could not be verified.',
    'user.2fa_enable_error' => 'Error: Could not enable 2 Factor Authentication',

    // user register errors
    'register.username.regex' => 'Username must consist of [a-zA-Z0-9_-] .',
    'register.username.unique' => 'Username you have picked is already in use, choose another one.',
    'register.username.min' => 'Username must be atleast :min characters long.',
    'register.email.unique' => 'The email you provided is already attached to an account.',
    'register.password' => 'You didn\'t confirm your password correctly, or the password has been declined for security reasons.',
    'register.password.min' => 'The password you entered needs to be longer than :min characters long.',

    'login.throttling' => 'Enabling this setting will prevent users from spamming the sites login form with invalid credentials.',
];
