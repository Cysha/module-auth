<?php

namespace Cms\Modules\Auth\Events\Handlers;

use Cms\Modules\Auth\Events\UserHasLoggedIn;

class CheckForEmptyEmail
{
    /**
     * Handle the event.
     *
     * @param UserLoggedIn $event
     */
    public function handle(UserHasLoggedIn $event)
    {
        \Debug::console('triggering CheckForEmptyEmail');

        $authModel = config('cms.auth.config.user_model');

        // find the user associated with this event
        $user = with(new $authModel())->find($event->userId);
        if ($user === null) {
            return;
        }

        // check for an email
        if ($user->email !== null) {
            return;
        }

        \Debug::console('setting actions.check_email');
        session(['actions.check_email' => 'pxcms.user.settings']);

        return true;
    }
}
