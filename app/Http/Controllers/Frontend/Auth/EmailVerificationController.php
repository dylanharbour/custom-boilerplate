<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Notifications\Frontend\Auth\VerifyEmailNotification;

/**
 * Class EmailVerificationController.
 */
class EmailVerificationController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * EmailVerificationController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * @param $token
     *
     * @return mixed
     */
    //@TODO: Come back and refacto this method to be email explicit (Prob the entire class is best!)
    public function confirm($token)
    {
        $this->user->confirmAccount($token);

        return redirect()->route('frontend.auth.login')->withFlashSuccess(trans('exceptions.frontend.auth.confirmation.success'));
    }

    /**
     * @param $user
     *
     * @return mixed
     */
    public function sendVerificationEmail(User $user)
    {
        $user->notify(new VerifyEmailNotification($user->email_verification_code));

        return redirect()->route('frontend.auth.login')->withFlashSuccess(trans('exceptions.frontend.auth.confirmation.resent'));
    }
}
