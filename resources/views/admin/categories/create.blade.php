@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            Add Category
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
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
                    <label>Parent Category</label>
                    <select name="parent_id" class="form-control">
                        <option value="">-- None --</option>
                        @foreach ($parents as $id => $title)
                            <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>

                <button class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
@endsection
