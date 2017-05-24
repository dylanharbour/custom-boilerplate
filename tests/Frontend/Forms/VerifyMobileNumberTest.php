<?php

use Tests\BrowserKitTestCase;
use App\Models\Access\User\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Frontend\Auth\VerifyMobileNumberNotification;

/**
 * Class MobileNumberVerifiedMiddlewareTest.
 */
class VerifyMobileNumberTest extends BrowserKitTestCase
{
    /**
     * @var User
     */
    public $verifiedUser;

    /**
     * @var User
     */
    public $unverifiedUser;

    public function setUp()
    {
        parent::setUp();

        $this->unverifiedUser = factory(User::class)->states('mobile_unverified')->create();
        $this->unverifiedUser->attachRole(3);

        $this->verifiedUser = factory(User::class)->states('mobile_verified')->create();
        $this->verifiedUser->attachRole(3);
    }

    public function testVerifyMobileNumberForm()
    {

        //set the middleware to enabled.
        Config::set('access.users.confirm_mobile', true);

        $this->actingAs($this->unverifiedUser)
            ->visit(route(homeRoute()))
            ->seePageIs(route('frontend.confirm.mobile.verify'))
            ->type('', 'confirmation_code')
            ->press('Verify')
            ->seePageIs(route('frontend.confirm.mobile.verify'))
            ->see('The confirmation code field is required.');

        $this->actingAs($this->unverifiedUser)
            ->visit(route(homeRoute()))
            ->seePageIs(route('frontend.confirm.mobile.verify'))
            ->type('0000', 'confirmation_code')
            ->press('Verify')
            ->seePageIs(route('frontend.confirm.mobile.verify'))
            ->see('The Code given is incorrect. Please try again.');

        $this->actingAs($this->unverifiedUser)
            ->visit(route(homeRoute()))
            ->seePageIs(route('frontend.confirm.mobile.verify'))
            ->type($this->unverifiedUser->mobile_verification_code, 'confirmation_code')
            ->press('Verify')
            ->seePageIs(route(homeRoute()))
            ->see('Your Mobile Number has been verified')
            ->seeInDatabase('users', [
                'id' => $this->unverifiedUser->id,
                'mobile_verified' => 1,
            ])
            ->assertTrue($this->unverifiedUser->fresh()->isMobileNumberVerified());

        $this->actingAs($this->verifiedUser)
            ->visit(route(homeRoute()))
            ->seePageIs(route(homeRoute()));
    }

    public function testVerifyFormCannotBeAccessedIfUserAreNotRequiredToVerifyTheirMobileNumber()
    {

        //set the middleware to enabled.
        Config::set('access.users.confirm_mobile', false);

        $this->actingAs($this->unverifiedUser)
            ->visit(route('frontend.confirm.mobile.verify'))
            ->seePageIs(route(homeRoute()))
            ->see('No Mobile Number Verification Required');
    }

    public function testVerifyFormCannotBeAccessedByAlreadyVerifiedUser()
    {

        //set the middleware to enabled.
        Config::set('access.users.confirm_mobile', true);

        $this->actingAs($this->verifiedUser)
            ->visit(route('frontend.confirm.mobile.verify'))
            ->seePageIs(route(homeRoute()))
            ->see('No Mobile Number Verification Required');
    }

    public function testUserCanRequestAResendOfVerificationCode()
    {
        Notification::fake();

        //set the middleware to enabled.
        Config::set('access.users.confirm_mobile', true);

        $this->actingAs($this->unverifiedUser)
            ->visit(route('frontend.confirm.mobile.verify'))
            ->seePageIs(route('frontend.confirm.mobile.verify'))
            ->press('Resend')
            ->seePageIs(route('frontend.confirm.mobile.verify'))
            ->see(trans('exceptions.frontend.auth.confirmation.mobile_resent'));

        Notification::assertSentTo(
            $this->unverifiedUser,
            VerifyMobileNumberNotification::class
        );
    }
}
