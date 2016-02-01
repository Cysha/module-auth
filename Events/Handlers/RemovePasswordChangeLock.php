<?php namespace Cms\Modules\Auth\Events\Handlers;

use Cms\Modules\Auth\Events\UserPasswordWasChanged;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class RemovePasswordChangeLock
{
    /**
     * Handle the event.
     *
     * @param  UserLoggedIn  $event
     */
    public function handle(UserPasswordWasChanged $event)
    {
        $user = $event->user;
        if ($user === null) {
            return;
        }

        // update expiry timer
        $user->pass_expires_on = Carbon::now()->addSeconds(config('cms.auth.config.users.password_age'));
        if (!$user->save()) {
            return redirect()->back()
                ->withErrors($user->getErrors());
        }

        // remove the lock
        Session::forget('actions.reset_pass');
    }

}
