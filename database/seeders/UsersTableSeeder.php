<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin User',
                'email'          => 'admin@admin.com',
                'password'       => Hash::make('password'),
                'remember_token' => null,
                'mobile'         => '',
                'user_type'      => 'admin',
            ],
            [
                'id'             => 2,
                'name'           => 'Editor User',
                'email'          => 'editor@example.com',
                'password'       => Hash::make('password'),
                'remember_token' => null,
                'mobile'         => '',
                'user_type'      => 'editor',
            ],
            [
                'id'             => 3,
                'name'           => 'Guest User',
                'email'          => 'guest@example.com',
                'password'       => Hash::make('password'),
                'remember_token' => null,
                'mobile'         => '',
                'user_type'      => 'guest',
            ],
        ];

        User::insert($users);
    }
}
