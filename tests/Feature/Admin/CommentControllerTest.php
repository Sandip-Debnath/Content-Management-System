<?php
namespace Tests\Feature\Controllers\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    protected function createUserWithPermission($permissions)
    {
        $user = User::create([
            'name'      => 'Admin_' . uniqid(),
            'email'     => uniqid() . '@example.com',
            'password'  => bcrypt('secret'),
            'user_type' => 'admin',
        ]);

        $role = Role::firstOrCreate(['title' => 'Test Role']);
        foreach ((array) $permissions as $perm) {
            $permission = Permission::firstOrCreate(['title' => $perm]);
            $role->permissions()->syncWithoutDetaching($permission->id);
        }
        $user->roles()->syncWithoutDetaching($role->id);

        return $user;
    }

    protected function createComment($status = 'pending')
    {
        $category = Category::firstOrCreate(
            ['slug' => 'cat-slug-' . uniqid()],
            ['title' => 'Cat_' . uniqid()]
        );

        $article = Article::create([
            'title'       => 'Article_' . uniqid(),
            'slug'        => 'article-slug-' . uniqid(),
            'content'     => 'Sample content',
            'category_id' => $category->id,
            'status'      => 'published',
            'author_id'   => User::first()->id ?? $this->createUserWithPermission([])->id,
        ]);

        return Comment::create([
            'article_id' => $article->id,
            'user_id'    => $article->author_id,
            'content'    => 'Test comment',
            'status'     => $status,
        ]);
    }

    /** @test */
    public function approve_route_updates_status()
    {
        $user = $this->createUserWithPermission(['comment_edit']);
        $this->actingAs($user);

        $comment = $this->createComment('pending');

        $response = $this->post(route('admin.comments.approve', $comment->id));
        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'id'     => $comment->id,
            'status' => 'approved',
        ]);
    }

    /** @test */
    public function reject_route_updates_status()
    {
        $user = $this->createUserWithPermission(['comment_edit']);
        $this->actingAs($user);

        $comment = $this->createComment('pending');

        $response = $this->post(route('admin.comments.reject', $comment->id));
        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'id'     => $comment->id,
            'status' => 'rejected',
        ]);
    }

    /** @test */
    public function destroy_route_removes_comment()
    {
        $user = $this->createUserWithPermission(['comment_delete']);
        $this->actingAs($user);

        $comment = $this->createComment();

        $response = $this->delete(route('admin.comments.destroy', $comment->id));
        $response->assertRedirect();

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }
}
