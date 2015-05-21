<?php namespace Cms\Modules\Auth\Events;

use Illuminate\Queue\SerializesModels;

class UserIsRegistering
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param object The request for this user registering.
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

}
