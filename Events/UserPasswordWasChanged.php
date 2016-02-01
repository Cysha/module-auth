<?php namespace Cms\Modules\Auth\Events;

use Illuminate\Queue\SerializesModels;

class UserPasswordWasChanged
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param object The user.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

}
