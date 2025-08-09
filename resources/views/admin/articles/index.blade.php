@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Articles</h5>
            @can('article_create')
                <a class="btn btn-success" href="{{ route('admin.articles.create') }}">
                    Add Article
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Published At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->category?->title }}</td>
                            <td>{{ $article->author?->name }}</td>
                            <td>{{ ucfirst($article->status) }}</td>
                            <td>
                                {{ $article->published_at ? \Illuminate\Support\Carbon::parse($article->published_at)->format('d M Y') : '-' }}
                            </td>
                            <td>
                                @can('article_show')
                                    <a href="{{ route('admin.articles.show', $article) }}" class="btn btn-sm btn-info">View</a>
                                @endcan
                                @can('article_edit')
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-primary">Edit</a>
                                @endcan
                                @can('article_delete')
                                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this article?')">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No articles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
