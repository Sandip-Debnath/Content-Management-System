<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $all = Permission::all();

        // Admin → all permissions
        Role::findOrFail(1)->permissions()->sync($all->pluck('id')->all());

        // Editor → categories + articles + comments + cms_access
        $editor = $all->filter(function ($perm) {
            return Str::startsWith($perm->title, ['category_', 'article_', 'comment_'])
            || $perm->title === 'cms_access';
        })->pluck('id')->all();

        Role::findOrFail(2)->permissions()->sync($editor);

        // Guest → can view articles + create comments
        $guest = $all->filter(function ($perm) {
            return in_array($perm->title, [
                'article_access',
                'article_show',
                'comment_create',
            ], true);
        })->pluck('id')->all();

        Role::findOrFail(3)->permissions()->sync($guest);
    }
}
