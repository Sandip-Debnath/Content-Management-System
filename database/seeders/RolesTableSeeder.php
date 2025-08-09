<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'Admin', // Full access
            ],
            [
                'id'    => 2,
                'title' => 'Editor', // Limited to managing articles & assigned categories
            ],
            [
                'id'    => 3,
                'title' => 'Guest', // Can view articles & post comments for approval
            ],
        ];

        Role::insert($roles);
    }
}
