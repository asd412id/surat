<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::where('username', 'admin')->delete();
        $admin = new User();
        $admin->name = 'Administrator';
        $admin->username = 'admin';
        $admin->password = bcrypt('password');
        $admin->role = 0;
        $admin->save();
    }
}
