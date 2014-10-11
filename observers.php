<?php

Event::listen('auth.user.register', function ($userInfo) {
    if (empty($userInfo)) {
        return false;
    }

    $authModel = Config::get('auth.model');
    $objUser = new $authModel;
    $objUser->hydrateFromInput($userInfo);

    if (Config::get('users::user.require_verifing') === false) {
        $objUser->verified = 1;
    }

    if ($objUser->save()) {
        return $objUser;
    }

    return false;
});
