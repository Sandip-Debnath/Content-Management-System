@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Categories</h5>
            @can('category_create')
                <a class="btn btn-success" href="{{ route('admin.categories.create') }}">
                    Add Category
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Parent</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->title }}</td>
                            <td>{{ $category->parent?->title ?? '-' }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ Str::limit($category->description, 50) }}</td>
                            <td>
                                @can('category_show')
                                    <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-info">View</a>
                                @endcan
                                @can('category_edit')
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                        class="btn btn-sm btn-primary">Edit</a>
                                @endcan
                                @can('category_delete')
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this category?')">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
