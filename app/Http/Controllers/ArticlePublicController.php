<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticlePublicController extends Controller
{
    /**
     * Show all published articles for the public feed.
     */
    public function index()
    {
        $articles = Article::with([
            'category:id,title',
            'author:id,name',
            'comments' => function ($q) {
                $q->where('status', 'approved')
                    ->with('user:id,name')
                    ->latest();
            },
        ])
            ->select('id', 'title', 'slug', 'content', 'category_id', 'author_id', 'published_at', 'status')
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->get();

        return view('frontend.articles.index', compact('articles'));
    }

    /**
     * Show a single published article with comments.
     */
    public function show(Article $article)
    {
        abort_unless($article->status === 'published', 404);

        $article->load([
            'category:id,title',
            'author:id,name',
            'comments' => function ($q) {
                $q->where('status', 'approved')
                    ->with('user:id,name')
                    ->latest();
            },
        ]);

        return view('frontend.articles.show', compact('article'));
    }

    /**
     * Store a comment for a given article.
     */
    public function storeComment(Request $request, Article $article)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Allow guests with the 'guest' role to comment
        $user = Auth::user();
        if (! $user || ! $user->can('comment_create')) {
            abort(403, 'You do not have permission to comment.');
        }

        $article->comments()->create([
            'user_id' => $user->id,
            'content' => $request->input('content'),
            'status'  => 'pending', 
        ]);

        return back()->with('success', 'Comment posted successfully.');
    }
}
