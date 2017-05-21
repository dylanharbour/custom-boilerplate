<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use App\Notifications\Frontend\Auth\VerifyEmailNotification;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;

/**
 * Class UserEmailVerificationController.
 */
class UserEmailVerificationController extends Controller
{
    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function sendVerificationEmail(User $user, ManageUserRequest $request)
    {
        $user->notify(new VerifyEmailNotification($user->email_verification_code));

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.users.confirmation_email'));
    }
}
