<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
{
    User::create([
        'username' => 'admin',
        'email' => 'superadmin@kiddosys.com',
        'password' => Hash::make('admin'),
    ]);

}
}
