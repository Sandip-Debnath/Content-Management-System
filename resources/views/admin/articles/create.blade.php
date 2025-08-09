@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">Add Article</div>
        <div class="card-body">
            <form action="{{ route('admin.articles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label>Slug (optional)</label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                </div>

                <div class="form-group">
                    <label>Category *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">-- Select --</option>
                        @foreach ($categories as $id => $title)
                            <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Content *</label>
                    <textarea name="content" class="form-control" rows="6" required>{{ old('content') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Publish Date</label>
                    <input type="date" name="published_at" class="form-control" value="{{ old('published_at') }}">
                </div>

                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection
