<?php

namespace Cms\Modules\Auth\Events\Handlers;

use Cms\Modules\Auth\Events\UserHasLoggedIn;

class CheckFor2Fa
{
    /**
     * Handle the event.
     *
     * @param UserLoggedIn $event
     */
    public function handle(UserHasLoggedIn $event)
    {
        \Debug::console('triggering CheckFor2Fa');

        $authModel = config('auth.model');

        // find the user associated with this event
        $user = with(new $authModel())->find($event->userId);
        if ($user === null) {
            return;
        }

        // make sure user has 2fa on
        if (!$user->require2fa) {
            return;
        }

        // set the session to enforce 2fa
        \Debug::console('setting actions.require_2fa');
        session(['actions.require_2fa' => 'pxcms.user.2fa']);

        return true;
    }
}
