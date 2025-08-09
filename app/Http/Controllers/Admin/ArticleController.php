<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('article_access'), Response::HTTP_FORBIDDEN);

        $articles = Article::with(['category', 'author'])
            ->when(Gate::allows('is_editor'), function ($q) {
                // Limit editor to their assigned categories if you store that relation
                $q->whereIn('category_id', Auth::user()->allowed_category_ids ?? []);
            })
            ->latest()
            ->get();

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        abort_if(Gate::denies('article_create'), Response::HTTP_FORBIDDEN);

        $categories = Category::pluck('title', 'id');
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('article_create'), Response::HTTP_FORBIDDEN);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:articles,slug',
            'content'      => 'required|string',
            'category_id'  => 'required|exists:categories,id',
            'status'       => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        // Auto-generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $data['author_id'] = Auth::id();

        Article::create($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article created successfully.');
    }

    public function show(Article $article)
    {
        // Guests can only view published
        if (Gate::denies('article_show') && $article->status !== 'published') {
            abort(Response::HTTP_FORBIDDEN);
        }

        $article->load(['category', 'author', 'comments' => function ($q) {
            $q->where('status', 'approved');
        }]);

        return view('admin.articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        abort_if(Gate::denies('article_edit'), Response::HTTP_FORBIDDEN);

        // If editor, ensure they own this category/article
        if (Gate::allows('is_editor') && ! in_array($article->category_id, Auth::user()->allowed_category_ids ?? [])) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $categories = Category::pluck('title', 'id');
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        abort_if(Gate::denies('article_edit'), Response::HTTP_FORBIDDEN);

        if (Gate::allows('is_editor') && ! in_array($article->category_id, Auth::user()->allowed_category_ids ?? [])) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:articles,slug,' . $article->id,
            'content'      => 'required|string',
            'category_id'  => 'required|exists:categories,id',
            'status'       => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        abort_if(Gate::denies('article_delete'), Response::HTTP_FORBIDDEN);

        $article->delete();
        return back()->with('success', 'Article deleted successfully.');
    }
}
