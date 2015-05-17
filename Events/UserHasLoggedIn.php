<?php namespace Cms\Modules\Auth\Events;

use Illuminate\Queue\SerializesModels;

class UserHasLoggedIn
{
    use SerializesModels;

    public $userId;

    /**
     * Create a new event instance.
     *
     * @param int userId the primary key of the user who was just authenticated.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

}
