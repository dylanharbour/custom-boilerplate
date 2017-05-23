<?php

namespace App\Events\Backend\Access\User;

use Illuminate\Queue\SerializesModels;

/**
 * Class UserPermanentlyDeletedEvent.
 */
class UserPermanentlyDeletedEvent
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
