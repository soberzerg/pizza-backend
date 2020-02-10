<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(\TopDigital\Auth\Models\User::class)
            ->create([
                'email' => 'admin@user.com',
                'password' => 'qwe123'
            ]);
    }
}
