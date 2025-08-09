@extends('layouts.app2')

@section('content')
    <div class="container mt-4">
        <div class="card mb-4 shadow-sm">
            @if ($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top" alt="{{ $article->title }}">
            @endif
            <div class="card-body">
                <h2>{{ $article->title }}</h2>
                <p class="text-muted">
                    Category: {{ $article->category->title ?? 'Uncategorized' }} |
                    Author: {{ $article->author->name ?? 'Unknown' }}
                </p>
                <p>{!! nl2br(e($article->content)) !!}</p>
            </div>
        </div>

        {{-- Approved Comments --}}
        <div class="mb-4">
            <h5>Comments</h5>
            @forelse($article->comments as $comment)
                <div class="border p-2 mb-2 rounded">
                    <strong>{{ $comment->user->name ?? 'Anonymous' }}</strong>
                    <p class="mb-0">{{ $comment->content }}</p>
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
            @empty
                <p class="text-muted">No comments yet.</p>
            @endforelse
        </div>

        {{-- Comment Form --}}
        @can('comment_create')
            <div class="card">
                <div class="card-header">Leave a Comment</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('frontend.articles.comments.store', $article) }}">
                        @csrf
                        <div class="form-group mb-2">
                            <textarea name="content" class="form-control" rows="3" placeholder="Write your comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">Post Comment</button>
                    </form>
                </div>
            </div>
        @endcan
    </div>
@endsection
