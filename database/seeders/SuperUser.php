<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate([
            'id'=> 1
        ],
            [
                'employee_id' => 211202052,
                'full_name' => 'Rakib Ibna Hamid',
                'nick_name' => 'Rakib',
                'email' => 'rakibhamid@appnap.io',
                'password' => Hash::make('welcome'),
                'is_super_user' => Config::get('variable_constants.check.yes'),
                'is_registration_complete' => Config::get('variable_constants.check.yes'),
                'is_password_changed' => Config::get('variable_constants.check.yes'),
                'status' => Config::get('variable_constants.check.yes'),
            ]);
    }
}
