<?php

namespace App\Notifications\Frontend\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class VerifyEmailNotification.
 */
class VerifyEmailNotification extends Notification
{
    use Queueable;

    /**
     * @var
     */
    protected $email_verification_code;

    /**
     * VerifyEmailNotification constructor.
     *
     * @param $email_verification_code
     */
    public function __construct($email_verification_code)
    {
        $this->email_verification_code = $email_verification_code;
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(app_name().': '.trans('exceptions.frontend.auth.confirmation.confirm'))
            ->line(trans('strings.emails.auth.click_to_confirm'))
            ->action(trans('buttons.emails.auth.confirm_account'), route('frontend.auth.account.confirm', $this->email_verification_code))
            ->line(trans('strings.emails.auth.thank_you_for_using_app'));
    }
}
