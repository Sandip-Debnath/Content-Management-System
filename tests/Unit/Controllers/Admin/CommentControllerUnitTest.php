<?php
namespace Tests\Unit\Controllers\Admin;

use App\Http\Controllers\Admin\CommentController;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CommentControllerUnitTest extends TestCase
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
        $this->controller = new CommentController();
    }

    protected function tearDown(): void
    {
        foreach (array_reverse($this->createdRecords) as $record) {
            $record->delete();
        }
        parent::tearDown();
    }

    protected function createComment($status = 'pending')
    {
        $category = Category::create([
            'title' => 'Cat_' . uniqid(),
            'slug'  => 'cat-slug-' . uniqid(),
        ]);
        $this->createdRecords[] = $category;

        $article = Article::create([
            'title'       => 'Article_' . uniqid(),
            'slug'        => 'article-slug-' . uniqid(),
            'content'     => 'Sample content',
            'category_id' => $category->id,
            'status'      => 'published',
            'author_id'   => $this->admin->id,
        ]);
        $this->createdRecords[] = $article;

        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id'    => $this->admin->id,
            'content'    => 'Test comment',
            'status'     => $status,
        ]);
        $this->createdRecords[] = $comment;

        return $comment;
    }

    /** @test */
    public function approve_changes_status_to_approved()
    {
        $comment  = $this->createComment();
        $response = $this->controller->approve($comment);

        $this->assertDatabaseHas('comments', [
            'id'     => $comment->id,
            'status' => 'approved',
        ]);
        $this->assertTrue($response->isRedirect());
    }

    /** @test */
    public function reject_changes_status_to_rejected()
    {
        $comment  = $this->createComment();
        $response = $this->controller->reject($comment);

        $this->assertDatabaseHas('comments', [
            'id'     => $comment->id,
            'status' => 'rejected',
        ]);
        $this->assertTrue($response->isRedirect());
    }

    /** @test */
    public function destroy_deletes_comment()
    {
        $comment  = $this->createComment();
        $response = $this->controller->destroy($comment);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
        $this->assertTrue($response->isRedirect());
    }
}
