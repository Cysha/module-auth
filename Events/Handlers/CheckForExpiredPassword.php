<?php namespace Cms\Modules\Auth\Events\Handlers;

use Cms\Modules\Auth\Events\UserHasLoggedIn;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckForExpiredPassword
{
    protected $request;

    /**
     * Create the event handler.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  UserLoggedIn  $event
     */
    public function handle(UserHasLoggedIn $event)
    {
        // check to see if passwords can expire
        if (config('cms.auth.config.users.expire_passwords', 'false') === 'false') {
            return;
        }

        // just bombing out here cause pxcms-auth#16
        return false;

        $authModel = config('auth.model');

        // find the user associated with this event
        $user = with(new $authModel)->find($event->userId);

        if ($user !== null) {
            return false;
        }

        // make sure theres actually an expiry
        if ($user->pass_expires_on !== null) {
            return false;
        }

        // TODO: Finish this off >.<
        if (Carbon::now()->gte($user->pass_expires_on)) {
            $user->password = '';
            $user->save();
        }
    }

}
