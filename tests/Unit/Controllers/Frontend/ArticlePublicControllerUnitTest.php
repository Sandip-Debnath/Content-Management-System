<?php
namespace Tests\Unit\Controllers\Frontend;

use App\Http\Controllers\ArticlePublicController;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class ArticlePublicControllerUnitTest extends TestCase
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
            'title'        => 'Unit Test Article',
            'slug'         => 'unit-article-' . uniqid(),
            'content'      => 'Unit content',
            'category_id'  => $category->id,
            'author_id'    => $author->id,
            'status'       => $status,
            'published_at' => now(),
        ]);
    }

    public function test_index_returns_only_published_articles()
    {
        $controller = new ArticlePublicController();
        $this->createArticle('published');
        $this->createArticle('draft');

        $view     = $controller->index();
        $articles = $view->getData()['articles'];
        $this->assertTrue($articles->every(fn($a) => $a->status === 'published'));
    }

    public function test_show_returns_view_for_published_article_and_404_for_draft()
    {
        $controller = new ArticlePublicController();
        $published  = $this->createArticle('published');
        $draft      = $this->createArticle('draft');

        $this->assertNotNull($controller->show($published));

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $controller->show($draft);
    }

}
