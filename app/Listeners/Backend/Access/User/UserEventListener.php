<?php

namespace App\Listeners\Backend\Access\User;

use App\Notifications\Frontend\Auth\VerifyEmailNotification;
use App\Notifications\Frontend\Auth\VerifyMobileNumberNotification;

/**
 * Class UserEventListener.
 */
class UserEventListener
{
    /**
     * @var string
     */
    private $history_slug = 'User';

    /**
     * @param $event
     */
    public function onCreated($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->user->id)
            ->withText('trans("history.backend.users.created") <strong>{user}</strong>')
            ->withIcon('plus')
            ->withClass('bg-green')
            ->withAssets([
                'user_link' => ['admin.access.user.show', $event->user->full_name, $event->user->id],
            ])
            ->log();

        //Send confirmation email if requested
        if (config('access.users.confirm_email') && ! $event->user->isEmailVerified()) {
            $event->user->notify(new VerifyEmailNotification($event->user->email_verification_code));
        }

        //Send verification email if requested
        if (config('access.users.confirm_mobile') && ! $event->user->isMobileNumberVerified()) {
            $event->user->notify(new VerifyMobileNumberNotification($event->user));
        }
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->user->id)
            ->withText('trans("history.backend.users.updated") <strong>{user}</strong>')
            ->withIcon('save')
            ->withClass('bg-aqua')
            ->withAssets([
                'user_link' => ['admin.access.user.show', $event->user->full_name, $event->user->id],
            ])
            ->log();
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->user->id)
            ->withText('trans("history.backend.users.deleted") <strong>{user}</strong>')
            ->withIcon('trash')
            ->withClass('bg-maroon')
            ->withAssets([
                'user_link' => ['admin.access.user.show', $event->user->full_name, $event->user->id],
            ])
            ->log();
    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->user->id)
            ->withText('trans("history.backend.users.restored") <strong>{user}</strong>')
            ->withIcon('refresh')
            ->withClass('bg-aqua')
            ->withAssets([
                'user_link' => ['admin.access.user.show', $event->user->full_name, $event->user->id],
            ])
            ->log();
    }

    /**
     * @param $event
     */
    public function onPermanentlyDeleted($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->user->id)
            ->withText('trans("history.backend.users.permanently_deleted") <strong>{user}</strong>')
            ->withIcon('trash')
            ->withClass('bg-maroon')
            ->withAssets([
                'user_string' => $event->user->full_name,
            ])
            ->log();

        history()->withType($this->history_slug)
            ->withEntity($event->user->id)
            ->withAssets([
                'user_string' => $event->user->full_name,
            ])
            ->updateUserLinkAssets();
    }

    /**
     * @param $event
     */
    public function onPasswordChanged($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->user->id)
            ->withText('trans("history.backend.users.changed_password") <strong>{user}</strong>')
            ->withIcon('lock')
            ->withClass('bg-blue')
            ->withAssets([
                'user_link' => ['admin.access.user.show', $event->user->full_name, $event->user->id],
            ])
            ->log();
    }

    /**
     * @param $event
     */
    public function onDeactivated($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->user->id)
            ->withText('trans("history.backend.users.deactivated") <strong>{user}</strong>')
            ->withIcon('times')
            ->withClass('bg-yellow')
            ->withAssets([
                'user_link' => ['admin.access.user.show', $event->user->full_name, $event->user->id],
            ])
            ->log();
    }

    /**
     * @param $event
     */
    public function onReactivated($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->user->id)
            ->withText('trans("history.backend.users.reactivated") <strong>{user}</strong>')
            ->withIcon('check')
            ->withClass('bg-green')
            ->withAssets([
                'user_link' => ['admin.access.user.show', $event->user->full_name, $event->user->id],
            ])
            ->log();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            \App\Events\Backend\Access\User\UserCreatedEvent::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onCreated'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserUpdatedEvent::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onUpdated'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserDeletedEvent::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onDeleted'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserRestoredEvent::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onRestored'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserPermanentlyDeletedEvent::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onPermanentlyDeleted'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserPasswordChangedEvent::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onPasswordChanged'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserDeactivatedEvent::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onDeactivated'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserReactivatedEvent::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onReactivated'
        );
    }
}
