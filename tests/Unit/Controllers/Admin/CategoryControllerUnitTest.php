<?php
namespace Tests\Unit\Controllers\Admin;

use App\Http\Controllers\Admin\CategoryController;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CategoryControllerUnitTest extends TestCase
{
    protected $admin;
    protected $controller;
    protected $createdRecords = [];

    protected function setUp(): void
    {
        parent::setUp();

        Gate::shouldReceive('denies')->andReturn(false);
        Gate::shouldReceive('allows')->andReturn(true);

        $this->admin = User::create([
            'name'      => 'Admin_' . uniqid(),
            'email'     => uniqid() . '@example.com',
            'password'  => bcrypt('secret'),
            'user_type' => 'admin',
        ]);
        $this->createdRecords[] = $this->admin;

        $this->actingAs($this->admin);
        $this->controller = new CategoryController();
    }

    protected function tearDown(): void
    {
        foreach (array_reverse($this->createdRecords) as $record) {
            $record->delete();
        }
        parent::tearDown();
    }

    /** @test */
    public function store_creates_category()
    {
        $request = Request::create(route('admin.categories.store'), 'POST', [
            'title' => 'Cat_' . uniqid(),
            'slug'  => 'slug-' . uniqid(),
        ]);

        $response = $this->controller->store($request);

        $this->assertDatabaseHas('categories', [
            'title' => $request->get('title'),
            'slug'  => $request->get('slug'),
        ]);
        $this->assertTrue($response->isRedirect(route('admin.categories.index')));
    }

    /** @test */
    public function update_modifies_category()
    {
        $category = Category::create([
            'title' => 'Old_' . uniqid(),
            'slug'  => 'slug-' . uniqid(),
        ]);
        $this->createdRecords[] = $category;

        $request = Request::create(route('admin.categories.update', $category->id), 'PUT', [
            'title' => 'Updated_' . uniqid(),
            'slug'  => 'updated-slug-' . uniqid(),
        ]);

        $response = $this->controller->update($request, $category);

        $this->assertDatabaseHas('categories', [
            'id'    => $category->id,
            'title' => $request->get('title'),
            'slug'  => $request->get('slug'),
        ]);
        $this->assertTrue($response->isRedirect(route('admin.categories.index')));
    }

    /** @test */
    public function destroy_deletes_category()
    {
        $category = Category::create([
            'title' => 'Del_' . uniqid(),
            'slug'  => 'slug-' . uniqid(),
        ]);
        $this->createdRecords[] = $category;

        $response = $this->controller->destroy($category);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
        $this->assertTrue($response->isRedirect());
    }
}
