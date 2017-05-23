<?php

namespace App\Listeners\Frontend\Auth;

use Log;
use App\Notifications\Frontend\Auth\VerifyEmailNotification;
use App\Notifications\Frontend\Auth\VerifyMobileNumberNotification;

/**
 * Class UserEventListener.
 */
class UserEventListener
{
    /**
     * @param $event
     */
    public function onLoggedIn($event)
    {
        Log::info('User Logged In: '.$event->user->full_name);
    }

    /**
     * @param $event
     */
    public function onLoggedOut($event)
    {
        Log::info('User Logged Out: '.$event->user->full_name);
    }

    /**
     * @param $event
     */
    public function onRegistered($event)
    {

        //Send confirmation email if requested
        if (config('access.users.confirm_email') && ! $event->user->isEmailVerified()) {
            $event->user->notify(new VerifyEmailNotification($event->user->email_verification_code));
        }

        //Send verification email if requested
        if (config('access.users.confirm_mobile') && ! $event->user->isMobileNumberVerified()) {
            $event->user->notify(new VerifyMobileNumberNotification($event->user));
        }

        Log::info('User Registered: '.$event->user->full_name);
    }

    /**
     * @param $event
     */
    public function onEmailConfirmed($event)
    {
        Log::info('User Email Confirmed: '.$event->user->email);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            \App\Events\Frontend\Auth\UserLoggedInEvent::class,
            'App\Listeners\Frontend\Auth\UserEventListener@onLoggedIn'
        );

        $events->listen(
            \App\Events\Frontend\Auth\UserLoggedOutEvent::class,
            'App\Listeners\Frontend\Auth\UserEventListener@onLoggedOut'
        );

        $events->listen(
            \App\Events\Frontend\Auth\UserRegisteredEvent::class,
            'App\Listeners\Frontend\Auth\UserEventListener@onRegistered'
        );

        $events->listen(
            \App\Events\Frontend\Auth\UserEmailConfirmedEvent::class,
            'App\Listeners\Frontend\Auth\UserEventListener@onEmailConfirmed'
        );
    }
}
