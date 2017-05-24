<?php

namespace App\Notifications\Frontend\Auth;

use Log;
use Illuminate\Bus\Queueable;
use App\Models\Access\User\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class VerifyMobileNumberNotification.
 */
class VerifyMobileNumberNotification extends Notification
{
    use Queueable;

    /**
     * @var User
     */
    protected $user;

    /**
     * VerifyMobileNumberNotification constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        if (config('services.nexmo.enabled')) {
            return ['nexmo'];
        }

        Log::info("Not Sending SMS Verification to {$this->user->full_name}. 
        Verification code is {$this->user->mobile_verification_code}");

        return [];
    }

    /***
     *
     * Get the Nexmo / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        if ($this->user->isMobileNumberVerified()) {
            Log::warning("User {$this->user->id} Mobile number already confirmed. Not Sending SMS!");
            throw new BadRequestHttpException();
        }

        return (new NexmoMessage())
            ->content(app_name().' SMS Confirmation Code: '.$this->user->mobile_verification_code);
    }
}
