@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">Comment Details</div>

        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td>{{ $comment->id }}</td>
                </tr>
                <tr>
                    <th>Article</th>
                    <td>{{ $comment->article?->title }}</td>
                </tr>
                <tr>
                    <th>User</th>
                    <td>{{ $comment->user?->name ?? 'Guest' }}</td>
                </tr>
                <tr>
                    <th>Content</th>
                    <td>{{ $comment->content }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($comment->status) }}</td>
                </tr>
                <tr>
                    <th>Submitted At</th>
                    <td>{{ $comment->created_at->format('d M Y H:i') }}</td>
                </tr>
            </table>

            <div class="mt-3">
                @if ($comment->status !== 'approved')
                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-success">Approve</button>
                    </form>
                @endif

                @if ($comment->status !== 'rejected')
                    <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-warning">Reject</button>
                    </form>
                @endif

                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger" onclick="return confirm('Delete this comment?')">
                        Delete
                    </button>
                </form>

                <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
@endsection
