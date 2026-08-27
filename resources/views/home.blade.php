@extends('layouts.app')

@section('title', 'EventSphere - Campus Event Discovery & Management')

@section('content')

{{-- ================================================
     HERO SECTION
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
            Centralized platform for college hackathons, cultural carnivals, sports meets, and technical symposiums. Register instantly, verify QR passes, and download digital certificates.
        </p>

        <div class="hero-buttons">
            <a href="{{ route('events.index') }}" class="btn btn-primary btn-xl">
                <i class="fa-solid fa-compass"></i> Explore Campus Events
            </a>
            @guest
            <a href="{{ route('register') }}" class="btn btn-outline btn-xl">
                <i class="fa-solid fa-user-plus"></i> Join as Student
            </a>
            <a href="{{ route('register') }}?role=organizer" class="btn btn-primary-light btn-xl">
                <i class="fa-solid fa-bullhorn"></i> Host an Event
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
        <i class="fa-solid fa-bullhorn" style="color:var(--primary);"></i>
        Campus Bulletins & Critical Alerts
    </div>
    <div>
        @foreach($announcements as $announcement)
        <div class="announcement-item">
            <div>
                <span style="font-weight:700; font-size:0.92rem; color:var(--text-main);">{{ $announcement->title }}</span>
                @if($announcement->message)
                    <p style="font-size:0.82rem; color:var(--text-muted); margin-top:0.2rem;">{{ Str::limit($announcement->message, 90) }}</p>
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
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <div>
                <div class="stat-number">{{ $totalOrganizers ?? 25 }}+</div>
                <div class="stat-label">Faculty Organizers</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-certificate"></i>
            </div>
            <div>
                <div class="stat-number">{{ $totalCertificates ?? 180 }}+</div>
                <div class="stat-label">Issued Certificates</div>
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     UPCOMING FEATURED EVENTS
     ================================================ --}}
<div style="max-width: 1320px; margin: 3.5rem auto 4.5rem; padding: 0 1.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;" class="reveal">
        <div>
            <div class="section-eyebrow"><i class="fa-solid fa-bolt" style="color:var(--primary); margin-right:0.3rem;"></i> Discover What's Happening</div>
            <h2 class="section-title">Upcoming College Events</h2>
        </div>
        <a href="{{ route('events.index') }}" class="btn btn-outline btn-sm">
            Browse All Events <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    @if($upcomingEvents->count() > 0)
        <div class="events-grid">
            @foreach($upcomingEvents as $event)
            <div class="event-card reveal">
                <div class="event-card-banner">
                    <img src="{{ $event->banner_image ? asset($event->banner_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80' }}"
                         alt="{{ $event->title }}"
                         onerror="this.src='https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80'">
                    <span class="event-badge">
                        <i class="fa-solid fa-tag"></i> {{ $event->category->name }}
                    </span>
                    @if($event->available_slots <= 0)
                        <span class="event-slots-tag" style="background:var(--danger);">Capacity Full</span>
                    @elseif($event->available_slots <= 10)
                        <span class="event-slots-tag" style="background:var(--warning);">Only {{ $event->available_slots }} Left</span>
                    @else
                        <span class="event-slots-tag">{{ $event->available_slots }} Slots Open</span>
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
                            <span>{{ $event->start_date->format('M d, Y • h:i A') }}</span>
                        </div>
                        <div class="event-meta-item">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>{{ $event->venue }}</span>
                        </div>
                        <div class="event-meta-item">
                            <i class="fa-solid fa-building-columns"></i>
                            <span>{{ $event->organizing_department ?? 'University Department' }}</span>
                        </div>
                    </div>

                    <div class="event-card-footer">
                        <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary btn-sm" style="flex:1;">
                            <i class="fa-solid fa-ticket"></i> View Details & Register
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
    @else
        <div class="card reveal" style="padding: 3.5rem; text-align: center;">
            <i class="fa-solid fa-calendar-xmark" style="font-size: 3rem; color: var(--primary-light); margin-bottom: 1rem;"></i>
            <h3>No upcoming events scheduled right now</h3>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Check back soon or explore past highlights in our media gallery!</p>
            <a href="{{ route('gallery.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-images"></i> Explore Gallery
            </a>
        </div>
    @endif
</div>

{{-- ================================================
     ORGANIZER PROMOTION SECTION
     ================================================ --}}
<div style="max-width: 1320px; margin: 0 auto 4.5rem; padding: 0 1.5rem;">
    <div class="card reveal" style="background: linear-gradient(135deg, #1E40AF 0%, #2563EB 50%, #0284C7 100%); color: #FFFFFF; border: none; border-radius: var(--radius-xl); padding: 3.5rem; position: relative; overflow: hidden; box-shadow: var(--shadow-xl);">
        <div style="position: absolute; right: -50px; bottom: -50px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2.5rem; align-items: center; position: relative; z-index: 2;" class="organizer-cta-grid">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); padding: 0.35rem 0.85rem; border-radius: var(--radius-full); font-size: 0.82rem; font-weight: 700; text-transform: uppercase; margin-bottom: 1rem;">
                    <i class="fa-solid fa-chalkboard-user"></i> Faculty & Club Leads
                </div>
                <h2 style="font-size: 2.25rem; font-weight: 800; color: #FFFFFF; line-height: 1.2; margin-bottom: 1rem;">
                    Host & Manage Campus Events with Ease
                </h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 1.05rem; line-height: 1.6; margin-bottom: 1.75rem;">
                    Are you a departmental faculty coordinator or student club lead? Register as an Organizer to submit event proposals for admin approval, track participant rosters, scan QR passes on event day, and issue verifiable digital certificates.
                </p>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="{{ route('register') }}?role=organizer" class="btn btn-secondary btn-lg" style="background: #FFFFFF; color: var(--primary-dark) !important; font-weight: 700;">
                        <i class="fa-solid fa-briefcase"></i> Register as Organizer
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-outline btn-lg" style="border-color: rgba(255,255,255,0.6); color: #FFFFFF !important;">
                        <i class="fa-solid fa-circle-info"></i> How It Works
                    </a>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.25); border-radius: var(--radius-lg); padding: 1.5rem; text-align: center;">
                    <i class="fa-solid fa-paper-plane" style="font-size: 2rem; margin-bottom: 0.75rem; color: #FFFFFF;"></i>
                    <h4 style="color: #FFFFFF; font-size: 1rem; margin-bottom: 0.3rem;">Event Proposals</h4>
                    <span style="font-size: 0.8rem; color: rgba(255,255,255,0.8);">Fast Admin Review</span>
                </div>
                <div style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.25); border-radius: var(--radius-lg); padding: 1.5rem; text-align: center;">
                    <i class="fa-solid fa-qrcode" style="font-size: 2rem; margin-bottom: 0.75rem; color: #FFFFFF;"></i>
                    <h4 style="color: #FFFFFF; font-size: 1rem; margin-bottom: 0.3rem;">QR Attendance</h4>
                    <span style="font-size: 0.8rem; color: rgba(255,255,255,0.8);">Instant Verification</span>
                </div>
                <div style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.25); border-radius: var(--radius-lg); padding: 1.5rem; text-align: center;">
                    <i class="fa-solid fa-certificate" style="font-size: 2rem; margin-bottom: 0.75rem; color: #FFFFFF;"></i>
                    <h4 style="color: #FFFFFF; font-size: 1rem; margin-bottom: 0.3rem;">E-Certificates</h4>
                    <span style="font-size: 0.8rem; color: rgba(255,255,255,0.8);">One-Click Batch Issuance</span>
                </div>
                <div style="background: rgba(255,255,255,0.12); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.25); border-radius: var(--radius-lg); padding: 1.5rem; text-align: center;">
                    <i class="fa-solid fa-bullhorn" style="font-size: 2rem; margin-bottom: 0.75rem; color: #FFFFFF;"></i>
                    <h4 style="color: #FFFFFF; font-size: 1rem; margin-bottom: 0.3rem;">Alerts & Updates</h4>
                    <span style="font-size: 0.8rem; color: rgba(255,255,255,0.8);">Notify Registered Users</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================================================
     EVENT CATEGORIES
     ================================================ --}}
<div style="max-width: 1320px; margin: 0 auto 4.5rem; padding: 0 1.5rem;">
    <div class="section-header reveal">
        <div class="section-eyebrow"><i class="fa-solid fa-layer-group" style="color:var(--primary); margin-right:0.3rem;"></i> Diverse Opportunities</div>
        <h2 class="section-title">Explore Event Categories</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        @foreach($categories as $cat)
        <a href="{{ route('events.index', ['category' => $cat->id]) }}" class="category-card reveal">
            <div class="category-icon-wrapper">
                <i class="fa-solid fa-{{ $cat->icon ?? 'star' }}"></i>
            </div>
            <div class="category-name">{{ $cat->name }}</div>
            <div class="category-count">{{ $cat->events_count }} Active Event{{ $cat->events_count != 1 ? 's' : '' }}</div>
        </a>
        @endforeach
    </div>
</div>

<style>
@media (max-width: 900px) {
    .organizer-cta-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

@endsection
