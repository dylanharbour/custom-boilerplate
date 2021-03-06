<?php

namespace App\Events\Frontend\Auth;

use Illuminate\Queue\SerializesModels;

/**
 * Class UserLoggedOutEvent.
 */
class UserLoggedOutEvent
{
    use SerializesModels;

    /**
     * @var
     */
    public $user;

    /**
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
