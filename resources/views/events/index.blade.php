@extends('layouts.app')

@section('title', 'EVENTS - EventSphere College Directory')

@section('content')
<div class="page-header reveal">
    <span class="section-label"><i class="fa-solid fa-compass" style="margin-right:0.3rem;"></i> Campus Directory</span>
    <h1 class="page-title">EVENTS</h1>
    <p class="page-subtitle">Discover upcoming college events, activities, workshops, and competitions.</p>
</div>

<!-- Search & Filter Area -->
<div class="filter-bar reveal">
    <form action="{{ route('events.index') }}" method="GET">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
            <div>
                <label class="form-label" style="font-size: 0.78rem;">
                    <i class="fa-solid fa-magnifying-glass" style="color:var(--primary-light); margin-right:0.3rem;"></i>
                    Search Events
                </label>
                <input type="text" name="search" class="form-control" placeholder="Search keywords, venue, dept..." value="{{ request('search') }}">
            </div>

            <div>
                <label class="form-label" style="font-size: 0.78rem;">
                    <i class="fa-solid fa-layer-group" style="color:var(--primary-light); margin-right:0.3rem;"></i>
                    Category
                </label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label" style="font-size: 0.78rem;">
                    <i class="fa-solid fa-building-columns" style="color:var(--primary-light); margin-right:0.3rem;"></i>
                    Department
                </label>
                <select name="department" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label" style="font-size: 0.78rem;">
                    <i class="fa-regular fa-calendar" style="color:var(--primary-light); margin-right:0.3rem;"></i>
                    Date From
                </label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>

            <div>
                <label class="form-label" style="font-size: 0.78rem;">
                    <i class="fa-regular fa-calendar-check" style="color:var(--primary-light); margin-right:0.3rem;"></i>
                    Date To
                </label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-subtle); padding-top: 1.25rem; flex-wrap: wrap; gap: 1rem;">
            <!-- Status Tabs -->
            <div class="tab-pills">
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'all'])) }}" class="tab-pill {{ $tab === 'all' ? 'active' : '' }}">
                    <i class="fa-solid fa-list" style="margin-right:0.3rem;"></i> All Events
                </a>
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'upcoming'])) }}" class="tab-pill {{ $tab === 'upcoming' ? 'active' : '' }}">
                    <i class="fa-solid fa-clock" style="margin-right:0.3rem;"></i> Upcoming
                </a>
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'ongoing'])) }}" class="tab-pill {{ $tab === 'ongoing' ? 'active' : '' }}">
                    <i class="fa-solid fa-bolt" style="margin-right:0.3rem;"></i> Ongoing
                </a>
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'past'])) }}" class="tab-pill {{ $tab === 'past' ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-check" style="margin-right:0.3rem;"></i> Past Events
                </a>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-filter"></i> Apply Filters
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Event Grid -->
@if($events->count() > 0)
    <div class="events-grid">
        @foreach($events as $event)
            <div class="event-card reveal">
                <div class="event-card-banner">
                    <img src="{{ $event->banner_image ? asset($event->banner_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80' }}"
                         alt="{{ $event->title }}"
                         onerror="this.src='https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80'">
                    
                    <span class="event-badge">
                        <i class="fa-solid fa-tag"></i> {{ $event->category->name }}
                    </span>

                    {{-- Event Status Badges --}}
                    @if($event->status === 'cancelled')
                        <span class="event-slots-tag" style="background:var(--danger); border-color:var(--danger);">
                            <i class="fa-solid fa-ban"></i> Cancelled
                        </span>
                    @elseif($event->end_date < now())
                        <span class="event-slots-tag" style="background:rgba(99,102,241,0.85);">
                            <i class="fa-solid fa-check-double"></i> Completed
                        </span>
                    @elseif($event->available_slots <= 0)
                        <span class="event-slots-tag" style="background:rgba(239,68,68,0.9);">
                            <i class="fa-solid fa-lock"></i> Full (Waitlist)
                        </span>
                    @elseif($event->start_date > now())
                        <span class="event-slots-tag" style="background:rgba(16,185,129,0.9);">
                            <i class="fa-solid fa-unlock"></i> Open ({{ $event->available_slots }} slots)
                        </span>
                    @else
                        <span class="event-slots-tag" style="background:rgba(245,158,11,0.9);">
                            <i class="fa-solid fa-circle-play"></i> Ongoing
                        </span>
                    @endif
                </div>

                <div class="event-card-body">
                    <h3 class="event-card-title">
                        <a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a>
                    </h3>
                    <p class="event-card-desc">{{ Str::limit(strip_tags($event->description), 95) }}</p>

                    <div class="event-meta">
                        <div class="event-meta-item">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span>{{ $event->start_date->format('M d, Y') }} • {{ $event->start_date->format('h:i A') }}</span>
                        </div>
                        <div class="event-meta-item">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>{{ $event->venue }}</span>
                        </div>
                        <div class="event-meta-item">
                            <i class="fa-solid fa-user-tie"></i>
                            <span>{{ $event->organizer->name }} ({{ $event->organizing_department ?? 'Campus Lead' }})</span>
                        </div>
                    </div>

                    <div class="event-card-footer">
                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary btn-sm" style="flex:1;">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> View Details
                        </a>
                        @auth
                        <form action="{{ route('events.bookmark', $event->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm" title="Bookmark Event">
                                <i class="fa-regular fa-bookmark"></i>
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div style="max-width: 1320px; margin: 0 auto 4rem; padding: 0 1.5rem; display: flex; justify-content: center;">
        {{ $events->links() }}
    </div>
@else
    <div class="card reveal" style="max-width: 800px; margin: 0 auto 4rem; padding: 3.5rem; text-align: center;">
        <i class="fa-solid fa-calendar-xmark" style="font-size: 3rem; color: var(--primary-light); margin-bottom: 1rem;"></i>
        <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; color: #FFFFFF;">No Events Found</h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Try adjusting your keyword search, category, or date filters to find matching campus events.</p>
        <a href="{{ route('events.index') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-rotate-left"></i> Reset All Filters
        </a>
    </div>
@endif

@endsection
