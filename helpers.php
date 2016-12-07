<?php

if (!function_exists('hasPermission')) {
    function hasPermission()
    {
        list($permission, $resource, $resource_id) = call_user_func_array('processPermission', func_get_args());

        return Lock::can($permission, $resource, $resource_id);
    }
}

if (!function_exists('processPermission')) {
    function processPermission()
    {
        $resource_id = null;
        if (func_num_args() === 1) {
            // explode the permission
            list($permission, $resource) = explode('@', func_get_arg(0));

            // check if there is an identifier in there
            if (strpos($resource, ':') !== false) {
                list($resource, $resource_id) = explode(':', $resource);
            }
        } elseif (func_num_args() === 3) {
            list($permission, $resource, $resource_id) = func_get_args();
        } elseif (func_num_args() === 2) {
            list($permission, $resource) = func_get_args();
        } else {
            return [];
        }

        if ($resource_id !== null) {
            $resource_id = (int) $resource_id;
        }

        return [$permission, $resource, $resource_id];
    }
}

if (!function_exists('checkForMentions')) {
    function checkForMentions($body)
    {
        $usernameValidation = config('cms.auth.config.users.username_validator', '\w+');
        preg_match_all('/\@('.$usernameValidation.')/', $body, $matches);

        return array_get($matches, '1', []);
    }
}

if (!function_exists('replaceMentions')) {
    function replaceMentions($body)
    {
        $usernameValidation = config('cms.auth.config.users.username_validator', '\w+');

        return preg_replace('/\@('.$usernameValidation.')/', '[\\0](/user/\\1)', $body);
    }
}
