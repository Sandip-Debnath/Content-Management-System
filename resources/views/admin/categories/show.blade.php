@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            Category Details
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td>{{ $category->id }}</td>
                </tr>
                <tr>
                    <th>Title</th>
                    <td>{{ $category->title }}</td>
                </tr>
                <tr>
                    <th>Slug</th>
                    <td>{{ $category->slug }}</td>
                </tr>
                <tr>
                    <th>Parent</th>
                    <td>{{ $category->parent?->title ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $category->description }}</td>
                </tr>
            </table>

            @if ($category->children->count())
                <h5>Subcategories</h5>
                <ul>
                    @foreach ($category->children as $child)
                        <li>{{ $child->title }}</li>
                    @endforeach
                </ul>
            @endif

            @if ($category->articles->count())
                <h5>Articles in this Category</h5>
                <ul>
                    @foreach ($category->articles as $article)
                        <li>{{ $article->title }}</li>
                    @endforeach
                </ul>
            @endif

            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@endsection
