<?php namespace Cms\Modules\Auth\Events;

use Illuminate\Queue\SerializesModels;

class UserHasRegistered
{
    use SerializesModels;

    public $userId;

    /**
     * Create a new event instance.
     *
     * @param int userId the primary key of the user who was just registered.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

}
