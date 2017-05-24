<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Repositories\Frontend\Access\User\UserRepository;
use App\Http\Requests\Frontend\Auth\VerifyMobileNumberRequest;
use App\Notifications\Frontend\Auth\VerifyMobileNumberNotification;

/**
 * Class MobileNumberVerificationController.
 */
class MobileNumberVerificationController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * MobileNumberVerificationController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        return view('frontend.auth.mobile_verify');
    }

    /**
     * @param $token
     *
     * @return mixed
     */
    public function confirm(VerifyMobileNumberRequest $request)
    {
        /** @var User $user */
        $user = access()->user();

        if (! $this->user->validatePhoneNumberVerificationCode(access()->user(), Input::get('confirmation_code'))) {
            return redirect()->back()->withInput()->withErrors('The Code given is incorrect. Please try again. ');
        }

        $user->mobile_verified = true;
        $user->save();

        return redirect()->route(homeRoute())->withSuccess('Your Mobile Number has been verified');
    }

    /**
     * @param $user
     *
     * @return mixed
     */
    public function sendConfirmationSms(User $user)
    {
        $user->notify(new VerifyMobileNumberNotification(access()->user()));

        return redirect()
            ->route('frontend.confirm.mobile.show')
            ->withFlashSuccess(trans('exceptions.frontend.auth.confirmation.mobile_resent'));
    }
}
