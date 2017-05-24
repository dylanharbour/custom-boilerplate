<?php

use Database\TruncateTable;
use Carbon\Carbon as Carbon;
use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;
use Illuminate\Support\Facades\DB;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        $this->truncate(config('access.users_table'));

        //Add the master administrator, user id of 1
        $users = [
            [
                'first_name'        => 'Admin',
                'last_name'         => 'Istrator',
                'email'             => 'admin@admin.com',
                'mobile_number'     => '+27829788843',
                'mobile_verified'         => true,
                'password'          => bcrypt('1234'),
                'email_verification_code' => md5(uniqid(mt_rand(), true)),
                'email_verified'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'Backend',
                'last_name'         => 'User',
                'email'             => 'executive@executive.com',
                'mobile_number'     => '+27829788843',
                'mobile_verified'         => true,
                'password'          => bcrypt('1234'),
                'email_verification_code' => md5(uniqid(mt_rand(), true)),
                'email_verified'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                'first_name'        => 'Default',
                'last_name'         => 'User',
                'email'             => 'user@user.com',
                'mobile_number'     => '+27829788843',
                'mobile_verified'         => true,
                'password'          => bcrypt('1234'),
                'email_verification_code' => md5(uniqid(mt_rand(), true)),
                'email_verified'         => true,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        DB::table(config('access.users_table'))->insert($users);

        $this->enableForeignKeys();
    }
}
