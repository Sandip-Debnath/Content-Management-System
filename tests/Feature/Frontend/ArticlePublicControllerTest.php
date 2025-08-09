<?php
namespace Tests\Feature\Frontend;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Tests\TestCase;

class ArticlePublicControllerTest extends TestCase
{
    protected function createCategory()
    {
        return Category::create([
            'title' => 'Category ' . uniqid(),
            'slug'  => 'slug-' . uniqid(),
        ]);
    }

    protected function createArticle($status = 'published')
    {
        $category = $this->createCategory();
        $author   = User::factory()->create();

        return Article::create([
            'title'        => 'Test Article',
            'slug'         => 'article-' . uniqid(),
            'content'      => 'Some content',
            'category_id'  => $category->id,
            'author_id'    => $author->id,
            'status'       => $status,
            'published_at' => now(),
        ]);
    }

    public function test_index_lists_only_published_articles()
    {
        $this->createArticle('published');
        $this->createArticle('draft');

        $response = $this->get(route('frontend.articles.index'));
        $response->assertStatus(200);
        $response->assertViewHas('articles', function ($articles) {
            return $articles->every(fn($a) => $a->status === 'published');
        });
    }

    public function test_show_returns_404_for_non_published()
    {
        $draft    = $this->createArticle('draft');
        $response = $this->get(route('frontend.articles.show', $draft->id));
        $response->assertStatus(404);
    }
}
