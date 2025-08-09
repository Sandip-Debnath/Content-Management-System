@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">Edit Article</div>
        <div class="card-body">
            <form action="{{ route('admin.articles.update', $article) }}" method="POST">
                @csrf @method('PUT')

                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $article->title) }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $article->slug) }}">
                </div>

                <div class="form-group">
                    <label>Category *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Select --</option>
                        @foreach ($categories as $id => $title)
                            <option value="{{ $id }}"
                                {{ old('category_id', $article->category_id) == $id ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Content *</label>
                    <textarea name="content" class="form-control" rows="6" required>{{ old('content', $article->content) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft
                        </option>
                        <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>
                            Published</option>
                        <option value="archived" {{ old('status', $article->status) == 'archived' ? 'selected' : '' }}>
                            Archived</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Publish Date</label>
                    <input type="date" name="published_at" class="form-control"
                        value="{{ old('published_at', $article->published_at ? \Illuminate\Support\Carbon::parse($article->published_at)->format('Y-m-d') : '') }}">
                </div>

                <button class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
@endsection
