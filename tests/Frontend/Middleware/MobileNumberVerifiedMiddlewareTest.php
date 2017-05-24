<?php

use Tests\BrowserKitTestCase;
use App\Models\Access\User\User;
use Illuminate\Support\Facades\Config;

/**
 * Class MobileNumberVerifiedMiddlewareTest.
 */
class MobileNumberVerifiedMiddlewareTest extends BrowserKitTestCase
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

    public function testMiddlewareEnabledBlocksUnverifiedUsers()
    {

        //set the middleware to enabled.
        Config::set('access.users.confirm_mobile', true);

        $this->actingAs($this->unverifiedUser)
            ->visit(route(homeRoute()))
            ->seePageIs(route('frontend.confirm.mobile.verify'));

        $this->actingAs($this->verifiedUser)
            ->visit(route(homeRoute()))
            ->seePageIs(route(homeRoute()));
    }

    public function testMiddlewareDisabledDoesNotBlocksUnverifiedUsers()
    {

        //set the middleware to disabled.
        Config::set('access.users.confirm_mobile', false);

        $this->actingAs($this->verifiedUser)
            ->visit(route(homeRoute()))
            ->seePageIs(route(homeRoute()));

        $this->actingAs($this->unverifiedUser)
            ->visit(route(homeRoute()))
            ->seePageIs(route(homeRoute()));
    }
}
