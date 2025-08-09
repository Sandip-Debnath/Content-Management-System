<?php
namespace Tests\Feature\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    protected $admin;
    protected $createdRecords = [];

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['title' => 'Admin']);

        $this->admin = User::create([
            'name'              => 'AdminUser_' . uniqid(),
            'email'             => uniqid() . '@example.com',
            'password'          => bcrypt('secret'),
            'user_type'         => 'admin',
            'email_verified_at' => now(),
        ]);
        $this->admin->roles()->sync([$adminRole->id]);

        $this->createdRecords[] = $this->admin;
    }

    protected function tearDown(): void
    {
        foreach (array_reverse($this->createdRecords) as $record) {
            $record->delete();
        }
        parent::tearDown();
    }

    /** @test */
    public function index_view()
    {
        $this->actingAs($this->admin, 'web')
            ->get(route('admin.articles.index'))
            ->assertOk()
            ->assertViewIs('admin.articles.index');
    }

    /** @test */
    public function store_creates_article()
    {
        $category               = Category::create(['title' => 'Cat_' . uniqid()]);
        $this->createdRecords[] = $category;

        $payload = [
            'title'       => 'Article_' . uniqid(),
            'slug'        => null,
            'content'     => 'Test content',
            'category_id' => $category->id,
            'status'      => 'draft',
        ];

        $this->actingAs($this->admin, 'web')
            ->post(route('admin.articles.store'), $payload)
            ->assertRedirect(route('admin.articles.index'));

        $this->assertDatabaseHas('articles', [
            'title'       => $payload['title'],
            'category_id' => $category->id,
            'status'      => 'draft',
        ]);
    }

    /** @test */
    public function edit_and_update_article()
    {
        $category               = Category::create(['title' => 'Cat_' . uniqid()]);
        $this->createdRecords[] = $category;

        $article = Article::create([
            'title'       => 'OldTitle_' . uniqid(),
            'slug'        => 'old-slug',
            'content'     => 'Old content',
            'category_id' => $category->id,
            'status'      => 'draft',
            'author_id'   => $this->admin->id,
        ]);
        $this->createdRecords[] = $article;

        $newData = [
            'title'       => 'NewTitle_' . uniqid(),
            'slug'        => null,
            'content'     => 'Updated content',
            'category_id' => $category->id,
            'status'      => 'published',
        ];

        $this->actingAs($this->admin, 'web')
            ->put(route('admin.articles.update', $article), $newData)
            ->assertRedirect(route('admin.articles.index'));

        $this->assertDatabaseHas('articles', [
            'id'     => $article->id,
            'title'  => $newData['title'],
            'status' => 'published',
        ]);
    }

    /** @test */
    public function destroy_deletes_article()
    {
        $category               = Category::create(['title' => 'Cat_' . uniqid()]);
        $this->createdRecords[] = $category;

        $article = Article::create([
            'title'       => 'DeleteMe_' . uniqid(),
            'slug'        => 'delete-me',
            'content'     => 'Test',
            'category_id' => $category->id,
            'status'      => 'draft',
            'author_id'   => $this->admin->id,
        ]);
        $this->createdRecords[] = $article;

        $this->actingAs($this->admin, 'web')
            ->delete(route('admin.articles.destroy', $article))
            ->assertRedirect();

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
