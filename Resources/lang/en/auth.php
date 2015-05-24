<?php

return [
    'permissions.authenticated' => 'You need to be authenticated to get there.',
    'permissions.unauthorized'  => 'You do not have the neccesary permissions for that. (:permission@:resource)',


    'user.deleted'              => 'Email / Password combination you provided cannot be found, Please try again.',
    'user.notfound'             => 'Email / Password combination you provided cannot be found, Please try again.',
    'user.incorrect_credentials' => 'Email / Password combination you provided cannot be found, Please try again.',

    'user.logged_out_successfully' => 'You have successfully logged out.',


    // user register errors
    'register.username.unique' => 'Username you have picked is already in use, choose another one.',
    'register.username.min'    => 'Username must be atleast :min characters long.',
    'register.email.unique'    => 'The email you provided is already attached to an account.',
    'register.password'        => 'You didn\'t confirm your password correctly, or the password has been declined for security reasons.',
    'register.password.min'    => 'The password you entered needs to be longer than :min characters long.',
];
