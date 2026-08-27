@extends('layouts.app')

@section('title', 'About EventSphere - Campus Event Management Ecosystem')

@section('content')
<div style="max-width: 960px; margin: 2rem auto;">
    <div style="text-align: center; margin-bottom: 3.5rem;" class="reveal">
        <span class="section-label">Our Mission & Vision</span>
        <h1 class="page-title" style="font-size: clamp(2rem, 4vw, 2.75rem); margin-top: 0.5rem; margin-bottom: 1rem;">About EventSphere</h1>
        <p class="page-subtitle" style="font-size: 1.05rem; max-width: 720px; margin: 0 auto; line-height: 1.7;">
            EventSphere was developed to solve the challenges of fragmented, traditional college event coordination. It serves as a unified digital platform connecting students, faculty organizers, and campus administration.
        </p>
    </div>

    <div class="feature-grid reveal" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.75rem; margin-bottom: 4rem;">
        <div class="feature-card">
            <div class="feature-icon" style="background: rgba(99, 102, 241, 0.12); color: var(--primary-light);">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <h3>Real-Time Communication</h3>
            <p>Eliminates missed noticeboard updates by delivering instant campus-wide announcements, venue alerts, and direct notifications.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon" style="background: rgba(34, 197, 94, 0.12); color: #4ADE80;">
                <i class="fa-solid fa-users-viewfinder"></i>
            </div>
            <h3>Dynamic Seating & Waitlists</h3>
            <p>Automatically enforces venue seating capacity rules and promotes waitlisted participants the moment registered seats free up.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon" style="background: rgba(139, 92, 246, 0.12); color: #A78BFA;">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <h3>Paperless QR Attendance</h3>
            <p>Generates unique encrypted QR passes for registered students, scanned seamlessly by staff at entry gates on event day.</p>
        </div>
    </div>

    <div class="card reveal" style="padding: 2.5rem; margin-bottom: 3rem; background: var(--bg-card); border: 1px solid var(--border-color);">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-main);">The Campus Event Lifecycle</h2>
        <p style="color: var(--text-muted); line-height: 1.8; margin-bottom: 1.5rem;">
            From initial proposal submission by departmental societies, through administrative approval workflows, to live registration counters and instant digital certificate distribution — EventSphere automates and streamlines every milestone.
        </p>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('events.index') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-compass"></i> Explore All Events</a>
            <a href="{{ route('faq') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-circle-question"></i> Read FAQs</a>
        </div>
    </div>
</div>
@endsection
