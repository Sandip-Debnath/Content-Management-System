<?php
namespace Tests\Unit\Controllers\Admin;

use App\Http\Controllers\Admin\ArticleController;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class ArticleControllerUnitTest extends TestCase
{
    protected $admin;
    protected $controller;
    protected $createdRecords = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Mock all gate checks to pass
        Gate::shouldReceive('denies')->andReturn(false);
        Gate::shouldReceive('allows')->andReturn(true);

        // Create admin user
        $this->admin = User::create([
            'name'      => 'Admin_' . uniqid(),
            'email'     => uniqid() . '@example.com',
            'password'  => bcrypt('secret'),
            'user_type' => 'admin',
        ]);
        $this->createdRecords[] = $this->admin;

        $this->actingAs($this->admin);

        $this->controller = new ArticleController();
    }

    protected function tearDown(): void
    {
        // Clean up created records in reverse order
        foreach (array_reverse($this->createdRecords) as $record) {
            $record->delete();
        }
        parent::tearDown();
    }

    /** @test */
    public function store_creates_article()
    {
        $category = Category::create([
            'title' => 'Cat_' . uniqid(),
            'slug'  => 'slug-cat-' . uniqid(),
        ]);
        $this->createdRecords[] = $category;

        $request = Request::create(route('admin.articles.store'), 'POST', [
            'title'       => 'Title_' . uniqid(),
            'slug'        => 'slug-' . uniqid(),
            'content'     => 'Test content',
            'category_id' => $category->id,
            'status'      => 'draft',
        ]);

        $response = $this->controller->store($request);

        $this->assertDatabaseHas('articles', [
            'title'  => $request->get('title'),
            'slug'   => $request->get('slug'),
            'status' => 'draft',
        ]);
        $this->assertTrue($response->isRedirect(route('admin.articles.index')));
    }

    /** @test */
    public function destroy_deletes_article()
    {
        $category = Category::create([
            'title' => 'CatDel_' . uniqid(),
            'slug'  => 'slug-catdel-' . uniqid(),
        ]);
        $this->createdRecords[] = $category;

        $article = Article::create([
            'title'       => 'Title_' . uniqid(),
            'slug'        => 'slug-' . uniqid(),
            'content'     => 'Some content',
            'category_id' => $category->id,
            'status'      => 'draft',
            'author_id'   => $this->admin->id,
        ]);
        $this->createdRecords[] = $article;

        $response = $this->controller->destroy($article);

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
        $this->assertTrue($response->isRedirect());
    }
}
