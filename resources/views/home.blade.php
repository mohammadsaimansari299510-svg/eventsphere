@extends('layouts.app')

@section('title', 'EVENTSPHERE - Campus Event Discovery & Management')

@section('content')

{{-- ================================================
     HERO SECTION (PROFESSIONAL DARK THEME)
     ================================================ --}}
<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="hero-glow"></div>
    <div class="hero-glow-2"></div>
    <div class="hero-shape hero-shape-1"></div>
    <div class="hero-shape hero-shape-2"></div>
    <div class="hero-shape hero-shape-3"></div>

    <div class="hero-content">
        <div class="hero-eyebrow">
            <i class="fa-solid fa-sparkles"></i>
            Official Campus Event Management Ecosystem
        </div>

        <h1 class="hero-title">
            Discover. <span class="highlight">Participate.</span><br>Experience Together.
        </h1>

        <p class="hero-subtitle">
            The modern centralized platform for college hackathons, cultural carnivals, sports meets, and technical symposiums. Discover events, secure entry passes, and download digital e-certificates.
        </p>

        <div class="hero-buttons">
            <a href="{{ route('events.index') }}" class="btn btn-primary btn-xl">
                <i class="fa-solid fa-compass"></i> Explore Events
            </a>
            @guest
            <a href="{{ route('register') }}" class="btn btn-outline btn-xl">
                <i class="fa-solid fa-user-plus"></i> Join as Student
            </a>
            <a href="{{ route('register') }}?role=organizer" class="btn btn-primary-light btn-xl">
                <i class="fa-solid fa-briefcase"></i> Host an Event
            </a>
            @else
            <a href="{{ route('gallery.index') }}" class="btn btn-outline btn-xl">
                <i class="fa-solid fa-photo-film"></i> Event Media Gallery
            </a>
            @endguest
        </div>
    </div>
</section>

{{-- ================================================
     CAMPUS ANNOUNCEMENTS
     ================================================ --}}
@if(isset($announcements) && $announcements->count() > 0)
<div class="announcements-banner reveal">
    <div class="announcements-title">
        <i class="fa-solid fa-bullhorn" style="color:var(--primary-light);"></i>
        Campus Bulletins & Critical Alerts
    </div>
    <div>
        @foreach($announcements as $announcement)
        <div class="announcement-item">
            <div>
                <strong style="font-size:0.92rem; color:#FFFFFF;">{{ $announcement->title }}</strong>
                @if($announcement->message)
                    <p style="font-size:0.85rem; color:var(--text-muted); margin-top:0.2rem;">{{ Str::limit($announcement->message, 100) }}</p>
                @endif
            </div>
            <span style="font-size:0.78rem; color:var(--text-dim); white-space:nowrap; flex-shrink:0;">
                <i class="fa-regular fa-clock" style="margin-right:0.2rem;"></i> {{ $announcement->created_at->diffForHumans() }}
            </span>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ================================================
     KEY METRICS & STATISTICS SECTION
     ================================================ --}}
<div class="stats-section reveal">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <div class="stat-number">{{ $totalEvents ?? 12 }}+</div>
                <div class="stat-label">Published Events</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div>
                <div class="stat-number">{{ $totalStudents ?? 450 }}+</div>
                <div class="stat-label">Active Students</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-users-gear"></i>
            </div>
            <div>
                <div class="stat-number">{{ $totalOrganizers ?? 28 }}+</div>
                <div class="stat-label">Faculty & Clubs</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <div class="stat-number">{{ $totalCertificates ?? 120 }}+</div>
                <div class="stat-label">Issued Certificates</div>
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     UPCOMING FEATURED EVENTS
     ================================================ --}}
<section style="max-width:1320px; margin:4.5rem auto 3rem; padding:0 1.5rem;" class="reveal">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:2.5rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <span class="section-eyebrow">Discover What's Happening</span>
            <h2 class="section-title">Upcoming Campus Events</h2>
        </div>
        <a href="{{ route('events.index') }}" class="btn btn-outline btn-sm">
            View Full Calendar <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
    <div class="events-grid" style="padding:0; margin:0;">
        @foreach($upcomingEvents as $event)
        <div class="event-card">
            <div class="event-card-banner">
                <img src="{{ $event->banner_image ? asset($event->banner_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80' }}"
                     alt="{{ $event->title }}"
                     onerror="this.src='https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80'">
                <span class="event-badge"><i class="fa-solid fa-tag"></i> {{ $event->category->name }}</span>
                @if($event->available_slots <= 0)
                    <span class="event-slots-tag" style="background:var(--danger);"><i class="fa-solid fa-lock"></i> Full</span>
                @else
                    <span class="event-slots-tag"><i class="fa-solid fa-unlock"></i> {{ $event->available_slots }} slots</span>
                @endif
            </div>
            <div class="event-card-body">
                <h3 class="event-card-title">
                    <a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a>
                </h3>
                <p class="event-card-desc">{{ Str::limit(strip_tags($event->description), 95) }}</p>
                <div class="event-meta">
                    <div class="event-meta-item">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ $event->start_date->format('M d, Y') }} • {{ $event->start_date->format('h:i A') }}</span>
                    </div>
                    <div class="event-meta-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>{{ $event->venue }}</span>
                    </div>
                    <div class="event-meta-item">
                        <i class="fa-solid fa-user-tie"></i>
                        <span>{{ $event->organizer->name }} ({{ $event->organizing_department ?? 'Campus' }})</span>
                    </div>
                </div>
                <div class="event-card-footer">
                    <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary btn-sm" style="flex:1;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <p style="color:var(--text-muted); text-align:center; padding:2rem 0;">No upcoming events currently scheduled.</p>
    @endif
</section>

{{-- ================================================
     ORGANIZER PROMOTIONAL CALLOUT
     ================================================ --}}
<section style="max-width:1320px; margin:4.5rem auto; padding:0 1.5rem;" class="reveal">
    <div style="background: linear-gradient(135deg, #121826 0%, #172033 100%); border:1px solid rgba(59,130,246,0.3); border-radius:var(--radius-xl); padding:3.5rem; position:relative; overflow:hidden; box-shadow:var(--shadow-xl);">
        <div style="position:absolute; right:-50px; top:-50px; width:280px; height:280px; background:radial-gradient(circle, rgba(59,130,246,0.2) 0%, transparent 70%); border-radius:50%; pointer-events:none;"></div>
        
        <div style="max-width:700px; position:relative; z-index:2;">
            <span class="section-eyebrow">
                <i class="fa-solid fa-bullhorn" style="margin-right:0.3rem;"></i> For Faculty & Student Clubs
            </span>
            <h2 style="font-size:2.4rem; font-weight:900; color:#FFFFFF; margin-bottom:1rem; line-height:1.2;">
                Host & Manage Your Events on EVENTSPHERE
            </h2>
            <p style="color:var(--text-muted); font-size:1.05rem; line-height:1.7; margin-bottom:2rem;">
                Empower your department or student society with proposal approvals, automated attendee registration tracking, live QR-code check-in scanning, and instant verified digital certificate distribution.
            </p>
            <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                <a href="{{ route('register') }}?role=organizer" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-briefcase"></i> Register as Organizer
                </a>
                <a href="{{ route('about') }}" class="btn btn-outline btn-lg">
                    <i class="fa-solid fa-circle-info"></i> Learn How It Works
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ================================================
     CATEGORIES SECTION
     ================================================ --}}
<section style="max-width:1320px; margin:4.5rem auto 5rem; padding:0 1.5rem;" class="reveal">
    <div style="text-align:center; margin-bottom:3rem;">
        <span class="section-eyebrow">Browse by Track</span>
        <h2 class="section-title">Explore Event Categories</h2>
    </div>

    @if(isset($categories) && $categories->count() > 0)
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem;">
        @foreach($categories as $category)
        <a href="{{ route('events.index') }}?category={{ $category->id }}" class="category-card">
            <div class="category-icon-wrapper">
                <i class="fa-solid fa-{{ $category->slug == 'technical' ? 'code' : ($category->slug == 'cultural' ? 'masks-theater' : ($category->slug == 'sports' ? 'trophy' : ($category->slug == 'workshops' ? 'laptop-code' : 'certificate'))) }}"></i>
            </div>
            <h4 class="category-name">{{ $category->name }}</h4>
            <span class="category-count">{{ $category->events_count ?? 0 }} Live Events</span>
        </a>
        @endforeach
    </div>
    @endif
</section>

@endsection
