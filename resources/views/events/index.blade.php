@extends('layouts.app')

@section('title', 'College Events Directory - EventSphere')

@section('content')
<div class="page-header reveal">
    <span class="section-label">Campus Catalog</span>
    <h1 class="page-title">College Events Directory</h1>
    <p class="page-subtitle">Filter and discover academic competitions, cultural shows, tech symposiums, workshops, and sports tournaments.</p>
</div>

<!-- Filters Bar -->
<div class="filter-bar reveal">
    <form action="{{ route('events.index') }}" method="GET">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <label class="form-label" style="font-size: 0.8rem;">Search Keywords</label>
                <input type="text" name="search" class="form-control" placeholder="Search title, venue, or dept..." value="{{ request('search') }}">
            </div>

            <div>
                <label class="form-label" style="font-size: 0.8rem;">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label" style="font-size: 0.8rem;">Organizing Department</label>
                <select name="department" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label" style="font-size: 0.8rem;">Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>

            <div>
                <label class="form-label" style="font-size: 0.8rem;">Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-subtle); padding-top: 1.25rem; flex-wrap: wrap; gap: 1rem;">
            <!-- Status Tabs -->
            <div class="tab-pills">
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'all'])) }}" class="tab-pill {{ $tab === 'all' ? 'active' : '' }}">All Events</a>
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'upcoming'])) }}" class="tab-pill {{ $tab === 'upcoming' ? 'active' : '' }}">Upcoming</a>
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'ongoing'])) }}" class="tab-pill {{ $tab === 'ongoing' ? 'active' : '' }}">Ongoing</a>
                <a href="{{ route('events.index', array_merge(request()->query(), ['tab' => 'past'])) }}" class="tab-pill {{ $tab === 'past' ? 'active' : '' }}">Past Events</a>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Apply Filters</button>
            </div>
        </div>
    </form>
</div>

<!-- Event Grid -->
@if($events->count() > 0)
    <div class="event-grid" data-stagger>
        @foreach($events as $event)
            <div class="event-card reveal">
                <div class="event-card-img-wrap bg-style" style="background-image: url('{{ $event->banner_image ? asset($event->banner_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80' }}');">
                    <span class="category-badge"><i class="fa-solid fa-tag"></i> {{ $event->category->name }}</span>
                    @if($event->isFull())
                        <span class="slot-badge full">Waitlist Active</span>
                    @elseif($event->available_slots <= 10)
                        <span class="slot-badge waitlist">{{ $event->available_slots }} Slots Left</span>
                    @else
                        <span class="slot-badge">{{ $event->available_slots }} Slots Left</span>
                    @endif
                </div>

                <div class="event-card-body">
                    <h3 class="event-card-title"><a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a></h3>
                    <p class="event-card-desc">
                        {{ Str::limit(strip_tags($event->description), 90) }}
                    </p>

                    <div class="event-meta">
                        <div class="meta-item"><i class="fa-regular fa-calendar-check" style="color: var(--primary-light);"></i> {{ $event->start_date->format('M d, Y • h:i A') }}</div>
                        <div class="meta-item"><i class="fa-solid fa-location-dot" style="color: var(--secondary);"></i> {{ $event->venue }}</div>
                        <div class="meta-item"><i class="fa-solid fa-building-user" style="color: var(--accent);"></i> {{ $event->organizing_department ?? 'Campus Authority' }}</div>
                    </div>

                    <div class="capacity-container">
                        <div class="capacity-header">
                            <span>Capacity</span>
                            <span>{{ $event->capacity - $event->available_slots }} / {{ $event->capacity }} Reserved</span>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width: {{ (($event->capacity - $event->available_slots) / max(1, $event->capacity)) * 100 }}%;"></div>
                        </div>
                    </div>

                    <div class="event-card-actions">
                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary btn-sm" style="flex: 1;">
                            View Event & Register
                        </a>
                        @auth
                        <form action="{{ route('events.bookmark', $event->id) }}" method="POST">
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

    <div style="margin-top: 3.5rem;">
        {{ $events->links() }}
    </div>
@else
    <div class="empty-state reveal">
        <i class="fa-solid fa-magnifying-glass empty-state-icon"></i>
        <h3>No events found matching your filter criteria</h3>
        <p>Try resetting your filters, expanding your date range, or searching for other keywords.</p>
        <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm" style="margin-top: 1.25rem;">Reset Filters</a>
    </div>
@endif
@endsection
