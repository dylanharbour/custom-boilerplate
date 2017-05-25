<?php

namespace App\Listeners\Shared\Access\User;

use App\Models\Access\User\User;
use App\Notifications\Frontend\Auth\VerifyMobileNumberNotification;

/**
 * Class UserEventListener.
 */
class UserEventListener
{
    /**
     * @param User $user
     * @return User
     */
    public function checkIfMobileNumberChanged(User $user)
    {
        if (
            config('access.users.confirm_mobile')
            && $user->isDirty('mobile_number')
        ) {
            $user->mobile_verification_code = $user->generateMobileVerifcationCode();
            $user->mobile_verified = false;
            $user->notify(new VerifyMobileNumberNotification($user));
        }

        return $user;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'eloquent.updating: '.User::class,
            static::class.'@checkIfMobileNumberChanged'

        );
    }
}
