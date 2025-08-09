@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">Comments</div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Article</th>
                        <th>User</th>
                        <th>Content</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr>
                            <td>{{ $comment->id }}</td>
                            <td>{{ $comment->article?->title }}</td>
                            <td>{{ $comment->user?->name ?? 'Guest' }}</td>
                            <td>{{ Str::limit($comment->content, 50) }}</td>
                            <td>
                                @if ($comment->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($comment->status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $comment->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.comments.show', $comment) }}" class="btn btn-sm btn-info">View</a>

                                @if ($comment->status !== 'approved')
                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                @endif

                                @if ($comment->status !== 'rejected')
                                    <form action="{{ route('admin.comments.reject', $comment) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-warning">Reject</button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this comment?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No comments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
