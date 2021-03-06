<?php

namespace App\Models\Access\User\Traits;

use App\Notifications\Frontend\Auth\PasswordResetNotification;

/**
 * Class UserSendPasswordReset.
 */
trait UserSendPasswordReset
{
    /**
     * Send the password reset notification.
     *
     * @param string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }
}
