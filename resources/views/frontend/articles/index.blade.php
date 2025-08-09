{{--  @extends('layouts.app2')  --}}

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Latest Articles</h2>

        @forelse($articles as $article)
            <div class="card mb-4 shadow-sm">
                @if ($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top" alt="{{ $article->title }}">
                @endif
                <div class="card-body">
                    <h4 class="card-title">
                        <a href="{{ route('frontend.articles.show', $article) }}">
                            {{ $article->title }}
                        </a>
                    </h4>
                    <p class="text-muted mb-1">
                        <small>
                            Category: {{ $article->category->title ?? 'Uncategorized' }} |
                            Author: {{ $article->author->name ?? 'Unknown' }}
                        </small>
                    </p>
                    <p class="card-text">
                        {{ Str::limit(strip_tags($article->content), 200) }}
                    </p>
                    <a href="{{ route('frontend.articles.show', $article) }}" class="btn btn-primary btn-sm">Read More</a>
                </div>

                {{-- Approved Comments --}}
                <div class="px-3 pb-2">
                    <h6 class="mt-3">Comments</h6>
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
                    <div class="card-footer">
                        <form method="POST" action="{{ route('frontend.articles.comments.store', $article) }}">
                            @csrf
                            <div class="form-group mb-2">
                                <textarea name="content" class="form-control" rows="2" placeholder="Write a comment..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm">Post Comment</button>
                        </form>
                    </div>
                @endcan
            </div>
        @empty
            <p class="text-muted">No articles found.</p>
        @endforelse
    </div>
@endsection
@extends('layouts.app2')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Latest Articles</h2>

        @forelse($articles as $article)
            <div class="card mb-4 shadow-sm">
                @if ($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top" alt="{{ $article->title }}">
                @endif
                <div class="card-body">
                    <h4 class="card-title">
                        <a href="{{ route('frontend.articles.show', $article) }}">
                            {{ $article->title }}
                        </a>
                    </h4>
                    <p class="text-muted mb-1">
                        <small>
                            Category: {{ $article->category->title ?? 'Uncategorized' }} |
                            Author: {{ $article->author->name ?? 'Unknown' }}
                        </small>
                    </p>
                    <p class="card-text">
                        {{ Str::limit(strip_tags($article->content), 200) }}
                    </p>
                    <a href="{{ route('frontend.articles.show', $article) }}" class="btn btn-primary btn-sm">Read More</a>
                </div>

                {{-- Approved Comments --}}
                <div class="px-3 pb-2">
                    <h6 class="mt-3">Comments</h6>
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
                    <div class="card-footer">
                        <form method="POST" action="{{ route('frontend.articles.comments.store', $article) }}">
                            @csrf
                            <div class="form-group mb-2">
                                <textarea name="content" class="form-control" rows="2" placeholder="Write a comment..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm">Post Comment</button>
                        </form>
                    </div>
                @endcan
            </div>
        @empty
            <p class="text-muted">No articles found.</p>
        @endforelse
    </div>
@endsection
