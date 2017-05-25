<?php

namespace App\Events\Backend\Access\User;

use App\Models\Access\User\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserCreatedEvent.
 */
class UserCreatedEvent
{
    use SerializesModels;

    /**
     * @var User
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
