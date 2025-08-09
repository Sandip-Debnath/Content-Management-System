<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentSubmitController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        Comment::create([
            'article_id' => $article->id,
            'user_id'    => Auth::id(),
            'content'    => $request->content,
            'status'     => 'pending',
        ]);

        return back()->with('success', 'Your comment has been submitted and is awaiting approval.');
    }
}
