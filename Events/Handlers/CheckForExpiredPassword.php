<?php namespace Cms\Modules\Auth\Events\Handlers;

use Cms\Modules\Auth\Events\UserHasLoggedIn;
use Carbon\Carbon;

class CheckForExpiredPassword
{
    /**
     * Handle the event.
     *
     * @param  UserLoggedIn  $event
     */
    public function handle(UserHasLoggedIn $event)
    {
        \Debug::console('triggering CheckForExpiredPassword');

        // check to see if passwords can expire
        //if (config('cms.auth.config.users.expire_passwords', 'false') === 'false') {
        //    return;
        //}

        $authModel = config('auth.model');

        // find the user associated with this event
        $user = with(new $authModel)->find($event->userId);
        if ($user === null) {
            return;
        }

        // make sure theres actually an expiry
        if ($user->pass_expires_on === null) {
            return;
        }

        // test to see if we have gone past the expiry
        if (!Carbon::now()->gte($user->pass_expires_on)) {
            return;
        }

        \Debug::console('setting actions.reset_pass');
        session(['actions.reset_pass' => 'pxcms.user.pass_expired']);
        return true;
    }

}
