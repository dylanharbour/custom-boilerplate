<?php

use Faker\Generator;
use App\Models\Access\Role\Role;
use App\Models\Access\User\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(User::class, function (Generator $faker) {
    static $password;

    return [
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'email'             => $faker->safeEmail,
        'mobile_verified' =>  true,
        'mobile_verification_code' => '1234',
        'password'          => $password ?: $password = bcrypt('secret'),
        'remember_token'    => str_random(10),
        'email_verification_code' => md5(uniqid(mt_rand(), true)),
        'email_verified' => true,
    ];
});

$factory->state(User::class, 'active', function () {
    return [
        'status' => 1,
    ];
});

$factory->state(User::class, 'inactive', function () {
    return [
        'status' => 0,
    ];
});

$factory->state(User::class, 'email_verified', function () {
    return [
        'email_verified' => 1,
    ];
});

$factory->state(User::class, 'unconfirmed', function () {
    return [
        'email_verified' => 0,
    ];
});

$factory->state(User::class, 'mobile_unverified', function () {
    return [
        'mobile_verified' => 0,
    ];
});

$factory->state(User::class, 'mobile_verified', function () {
    return [
        'mobile_verified' => 1,
    ];
});

/*
 * Roles
 */
$factory->define(Role::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
        'all'  => 0,
        'sort' => $faker->numberBetween(1, 100),
    ];
});

$factory->state(Role::class, 'admin', function () {
    return [
        'all' => 1,
    ];
});
