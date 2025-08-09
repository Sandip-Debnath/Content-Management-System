<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        // Assign Admin role to first user
        $admin = User::find(1);
        if ($admin) {
            $admin->roles()->sync([1]); // Admin role ID
        }

        // Example: Assign Editor role to second user
        $editor = User::find(2);
        if ($editor) {
            $editor->roles()->sync([2]); // Editor role ID
        }

        // Example: Assign Guest role to third user
        $guest = User::find(3);
        if ($guest) {
            $guest->roles()->sync([3]); // Guest role ID
        }
    }
}
