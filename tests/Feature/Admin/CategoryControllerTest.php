<?php
namespace Tests\Feature\Controllers\Admin;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use WithFaker;

    protected function createUserWithPermission($permissions)
    {
        $user = User::factory()->create(['user_type' => 'admin']);
        $role = Role::create(['title' => 'Test Role ' . uniqid()]);

        foreach ((array) $permissions as $perm) {
            $permission = Permission::firstOrCreate(['title' => $perm]);
            $role->permissions()->syncWithoutDetaching($permission->id);
        }

        $user->roles()->syncWithoutDetaching($role->id);
        return $user;
    }

    /** @test */
    public function store_route_creates_category()
    {
        $user = $this->createUserWithPermission(['category_create']);
        $this->actingAs($user);

        $uniqueSlug = 'test-category-' . uniqid();

        $data = [
            'title'       => 'Test Category ' . uniqid(),
            'slug'        => $uniqueSlug,
            'description' => 'Sample desc',
        ];

        $response = $this->post(route('admin.categories.store'), $data);
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['slug' => $uniqueSlug]);
    }

    /** @test */
    public function update_route_modifies_category()
    {
        $user = $this->createUserWithPermission(['category_edit']);
        $this->actingAs($user);

        $category = Category::create([
            'title' => 'Old Cat ' . uniqid(),
            'slug'  => 'old-cat-' . uniqid(),
        ]);

        $newSlug = 'new-cat-' . uniqid();

        $data = [
            'title' => 'New Cat ' . uniqid(),
            'slug'  => $newSlug,
        ];

        $response = $this->put(route('admin.categories.update', $category->id), $data);
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['slug' => $newSlug]);
    }

    /** @test */
    public function destroy_route_deletes_category()
    {
        $user = $this->createUserWithPermission(['category_delete']);
        $this->actingAs($user);

        $category = Category::create([
            'title' => 'To Delete ' . uniqid(),
            'slug'  => 'to-delete-' . uniqid(),
        ]);

        $response = $this->delete(route('admin.categories.destroy', $category->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
