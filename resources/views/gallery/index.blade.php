@extends('layouts.app')

@section('title', 'Campus Media Gallery - EventSphere')

@section('content')
<div class="page-header reveal" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
    <div>
        <span class="section-label">Moments & Highlights</span>
        <h1 class="page-title">Campus Media Gallery</h1>
        <p class="page-subtitle">Explore photos and high-definition video captures from recent college fests, symposiums, and sports meets.</p>
    </div>

    @auth
        @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
            <button data-modal-target="uploadMediaModal" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Media
            </button>
        @endif
    @endauth
</div>

<!-- Category & Department Filters -->
<div class="filter-bar reveal">
    <form action="{{ route('gallery.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-size: 0.78rem;">Category</label>
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1; min-width: 180px;">
            <label class="form-label" style="font-size: 0.78rem;">Department</label>
            <select name="department" class="form-select">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1; min-width: 140px;">
            <label class="form-label" style="font-size: 0.78rem;">Media Type</label>
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Photos</option>
                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Videos</option>
            </select>
        </div>

        <div style="display: flex; gap: 0.5rem; align-self: flex-end; padding-bottom: 2px;">
            <a href="{{ route('gallery.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
        </div>
    </form>
</div>

<!-- Gallery Grid -->
@if($mediaItems->count() > 0)
    <div class="gallery-grid" data-stagger>
        @foreach($mediaItems as $item)
            <div class="gallery-item reveal" data-lightbox="{{ asset($item->file_path) }}" data-type="{{ $item->media_type }}">
                @if($item->media_type === 'video')
                    <video src="{{ asset($item->file_path) }}" muted style="width: 100%; height: 100%; object-fit: cover;"></video>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 44px; height: 44px; background: rgba(0,0,0,0.6); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem;">
                        <i class="fa-solid fa-play"></i>
                    </div>
                @else
                    <img src="{{ asset($item->file_path) }}" alt="{{ $item->title }}" loading="lazy">
                @endif

                <span class="category-badge" style="top: 0.75rem; left: 0.75rem; font-size: 0.7rem; z-index: 5;">
                    {{ $item->category }}
                </span>

                @auth
                    <form action="{{ route('gallery.favorite', $item->id) }}" method="POST" style="position: absolute; top: 0.75rem; right: 0.75rem; z-index: 10;" onclick="event.stopPropagation();">
                        @csrf
                        <button type="submit" class="btn btn-sm" style="background: rgba(8, 13, 26, 0.75); backdrop-filter: blur(6px); border: 1px solid var(--border-color); color: {{ in_array($item->id, $savedMediaIds) ? '#EF4444' : '#F8FAFC' }}; width: 34px; height: 34px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-{{ in_array($item->id, $savedMediaIds) ? 'solid' : 'regular' }} fa-heart"></i>
                        </button>
                    </form>
                @endauth

                <div class="gallery-overlay">
                    <div class="gallery-overlay-content">
                        <h4>{{ $item->title }}</h4>
                        <span style="font-size: 0.75rem; color: var(--text-dim);">{{ $item->department ?? 'Campus Life' }} • {{ $item->year }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 3.5rem;">
        {{ $mediaItems->links() }}
    </div>
@else
    <div class="empty-state reveal">
        <i class="fa-solid fa-images empty-state-icon"></i>
        <h3>Media Gallery is Empty</h3>
        <p>Faculty organizers can upload event photos and highlight reels directly from their portal!</p>
    </div>
@endif

<!-- Upload Media Modal (Organizers & Admins) -->
@auth
    @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
        <div id="uploadMediaModal" class="modal-backdrop">
            <div class="modal-card">
                <div class="modal-header">
                    <h3 class="modal-title"><i class="fa-solid fa-cloud-arrow-up" style="color: var(--primary-light);"></i> Upload Media to Gallery</h3>
                    <button data-modal-close class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form action="{{ route('gallery.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label required">Media Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Annual Fest 2026 Opening Performance" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Media Type</label>
                            <select name="media_type" class="form-select" required>
                                <option value="image">Photo (JPG, PNG, WEBP)</option>
                                <option value="video">Video (MP4, MOV)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Category</label>
                            <select name="category" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Organizing Department</label>
                            <input type="text" name="department" class="form-control" placeholder="Computer Science & Eng">
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Year</label>
                            <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Select Media File</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>

                    <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem;">
                        <button type="button" data-modal-close class="btn btn-secondary btn-sm">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-upload"></i> Start Upload</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endauth
@endsection
