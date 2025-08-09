<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('comment_access'), Response::HTTP_FORBIDDEN);

        $comments = Comment::with(['article', 'user'])->latest()->get();
        return view('admin.comments.index', compact('comments'));
    }

    public function approve(Comment $comment)
    {
        abort_if(Gate::denies('comment_edit'), Response::HTTP_FORBIDDEN);

        $comment->update(['status' => 'approved']);
        return back()->with('success', 'Comment approved.');
    }

    public function reject(Comment $comment)
    {
        abort_if(Gate::denies('comment_edit'), Response::HTTP_FORBIDDEN);

        $comment->update(['status' => 'rejected']);
        return back()->with('success', 'Comment rejected.');
    }

    public function destroy(Comment $comment)
    {
        abort_if(Gate::denies('comment_delete'), Response::HTTP_FORBIDDEN);

        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}
