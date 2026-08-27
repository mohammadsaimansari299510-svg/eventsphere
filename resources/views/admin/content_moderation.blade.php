@extends('layouts.portal')

@section('title', 'Content Moderation - Admin Portal')

@section('content')
<div class="portal-topbar">
    <div class="portal-topbar-left">
        <h1 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">
            <i class="fa-solid fa-filter-circle-xmark" style="color: var(--secondary); margin-right: 0.4rem;"></i>
            Content Moderation Hub
        </h1>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
            Review student ratings, feedback comments, and gallery media to maintain campus guidelines
        </p>
    </div>
</div>

<div class="portal-main">
    <!-- Student Feedback Moderation Table -->
    <div class="card reveal" style="padding: 2rem; margin-bottom: 3rem;">
        <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.6rem;">
            <i class="fa-solid fa-comments" style="color: var(--primary-light); margin-right: 0.4rem;"></i>
            Student Feedback & Reviews
        </h3>

        @if($feedbacks->count() > 0)
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Event Title</th>
                            <th>User Name</th>
                            <th>Role Title</th>
                            <th>Overall Rating</th>
                            <th>Comment Text</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feedbacks as $fb)
                            <tr>
                                <td><strong>{{ $fb->event->title ?? 'Deleted Event' }}</strong></td>
                                <td>{{ $fb->user->name ?? 'User' }}</td>
                                <td><span class="badge badge-secondary">{{ $fb->user_role_title }}</span></td>
                                <td><span style="color: var(--warning); font-weight: 700;">{{ $fb->overall_rating }} ★</span></td>
                                <td style="max-width: 320px; line-height: 1.5;">{{ $fb->comments ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('admin.content.feedback.delete', $fb->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this feedback entry?');">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 2rem;">
                {{ $feedbacks->links() }}
            </div>
        @else
            <p style="color: var(--text-muted); text-align: center; padding: 2rem;">No feedback entries to moderate.</p>
        @endif
    </div>

    <!-- Media Gallery Moderation -->
    <div class="card reveal" style="padding: 2rem;">
        <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.6rem;">
            <i class="fa-solid fa-photo-film" style="color: var(--secondary); margin-right: 0.4rem;"></i>
            Media Gallery Uploads
        </h3>

        @if($mediaList->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem;">
                @foreach($mediaList as $media)
                    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
                        <div style="height: 150px; background: #000; overflow: hidden; position: relative;">
                            @if($media->media_type === 'video')
                                <video src="{{ asset($media->file_path) }}" style="width: 100%; height: 100%; object-fit: cover;"></video>
                            @else
                                <img src="{{ asset($media->file_path) }}" alt="{{ $media->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <div style="padding: 1rem;">
                            <h4 style="font-size: 0.95rem; font-weight: 600; margin-bottom: 0.25rem;">{{ $media->title }}</h4>
                            <p style="font-size: 0.78rem; color: var(--text-dim); margin-bottom: 1rem;">Uploaded by {{ $media->uploader->name ?? 'User' }}</p>

                            <form action="{{ route('admin.content.media.delete', $media->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger w-full" onclick="return confirm('Delete this media file?');">
                                    <i class="fa-solid fa-trash"></i> Delete Media
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div style="margin-top: 2rem;">
                {{ $mediaList->links() }}
            </div>
        @else
            <p style="color: var(--text-muted); text-align: center; padding: 2rem;">No media uploads to display.</p>
        @endif
    </div>
</div>
@endsection
