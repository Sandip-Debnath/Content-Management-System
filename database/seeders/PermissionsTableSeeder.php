<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User Management
            ['id' => 1, 'title' => 'user_management_access'],

            // Permissions
            ['id' => 2, 'title' => 'permission_create'],
            ['id' => 3, 'title' => 'permission_edit'],
            ['id' => 4, 'title' => 'permission_show'],
            ['id' => 5, 'title' => 'permission_delete'],
            ['id' => 6, 'title' => 'permission_access'],

            // Roles
            ['id' => 7, 'title' => 'role_create'],
            ['id' => 8, 'title' => 'role_edit'],
            ['id' => 9, 'title' => 'role_show'],
            ['id' => 10, 'title' => 'role_delete'],
            ['id' => 11, 'title' => 'role_access'],

            // Users
            ['id' => 12, 'title' => 'user_create'],
            ['id' => 13, 'title' => 'user_edit'],
            ['id' => 14, 'title' => 'user_show'],
            ['id' => 15, 'title' => 'user_delete'],
            ['id' => 16, 'title' => 'user_access'],

            // Audit Logs
            ['id' => 17, 'title' => 'audit_log_show'],
            ['id' => 18, 'title' => 'audit_log_access'],

            // Settings
            ['id' => 19, 'title' => 'setting_create'],
            ['id' => 20, 'title' => 'setting_edit'],
            ['id' => 21, 'title' => 'setting_show'],
            ['id' => 22, 'title' => 'setting_delete'],
            ['id' => 23, 'title' => 'setting_access'],

            // Profile
            ['id' => 24, 'title' => 'profile_password_edit'],

            // Country
            ['id' => 30, 'title' => 'country_create'],
            ['id' => 31, 'title' => 'country_edit'],
            ['id' => 32, 'title' => 'country_show'],
            ['id' => 33, 'title' => 'country_delete'],
            ['id' => 34, 'title' => 'country_access'],

            // State
            ['id' => 35, 'title' => 'state_create'],
            ['id' => 36, 'title' => 'state_edit'],
            ['id' => 37, 'title' => 'state_show'],
            ['id' => 38, 'title' => 'state_delete'],
            ['id' => 39, 'title' => 'state_access'],

            // City
            ['id' => 40, 'title' => 'city_create'],
            ['id' => 41, 'title' => 'city_edit'],
            ['id' => 42, 'title' => 'city_show'],
            ['id' => 43, 'title' => 'city_delete'],
            ['id' => 44, 'title' => 'city_access'],

            // Menu Group Access
            ['id' => 45, 'title' => 'detail_access'],

            // =========================
            // 📂 CMS Permissions
            // =========================
            ['id' => 46, 'title' => 'cms_access'],

            // Categories
            ['id' => 47, 'title' => 'category_create'],
            ['id' => 48, 'title' => 'category_edit'],
            ['id' => 49, 'title' => 'category_show'],
            ['id' => 50, 'title' => 'category_delete'],
            ['id' => 51, 'title' => 'category_access'],

            // Articles
            ['id' => 52, 'title' => 'article_create'],
            ['id' => 53, 'title' => 'article_edit'],
            ['id' => 54, 'title' => 'article_show'],
            ['id' => 55, 'title' => 'article_delete'],
            ['id' => 56, 'title' => 'article_access'],

            // Comments
            ['id' => 57, 'title' => 'comment_create'],
            ['id' => 58, 'title' => 'comment_edit'],
            ['id' => 59, 'title' => 'comment_show'],
            ['id' => 60, 'title' => 'comment_delete'],
            ['id' => 61, 'title' => 'comment_access'],
        ];

        Permission::insert($permissions);
    }
}
