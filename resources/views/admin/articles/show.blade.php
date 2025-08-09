@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">Article Details</div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td>{{ $article->id }}</td>
                </tr>
                <tr>
                    <th>Title</th>
                    <td>{{ $article->title }}</td>
                </tr>
                <tr>
                    <th>Slug</th>
                    <td>{{ $article->slug }}</td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>{{ $article->category?->title }}</td>
                </tr>
                <tr>
                    <th>Author</th>
                    <td>{{ $article->author?->name }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($article->status) }}</td>
                </tr>
                <tr>
                    <th>Published At</th>
                    <td>
                        {{ $article->published_at ? \Illuminate\Support\Carbon::parse($article->published_at)->format('d M Y') : '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Content</th>
                    <td>{!! nl2br(e($article->content)) !!}</td>
                </tr>
            </table>

            <h5>Approved Comments</h5>
            @if ($article->comments->count())
                <ul>
                    @foreach ($article->comments as $comment)
                        <li>{{ $comment->content }} — <small>{{ $comment->user?->name ?? 'Guest' }}</small></li>
                    @endforeach
                </ul>
            @else
                <p>No approved comments yet.</p>
            @endif

            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@endsection
