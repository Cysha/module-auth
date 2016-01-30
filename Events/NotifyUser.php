<?php namespace Cms\Modules\Auth\Events;

use Illuminate\Queue\SerializesModels;

class NotifyUser
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param integer $userId The user if to notify
     * @param string $template What to send ot the user
     */
    public function __construct($userId, $template)
    {
        $this->userId = $userId;
        $this->template = $template
    }

}
