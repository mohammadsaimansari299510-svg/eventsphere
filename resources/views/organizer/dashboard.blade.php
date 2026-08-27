@extends('layouts.portal')

@section('title', 'Organizer Dashboard - EventSphere')

@section('content')
<div class="portal-topbar">
    <div class="portal-topbar-left">
        <h1 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">Organizer Portal</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
            Create event proposals, track student registrations, verify attendance QR tokens & issue e-certificates
        </p>
    </div>

    <a href="{{ route('organizer.events.create') }}" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus-circle"></i> Create New Event Proposal
    </a>
</div>

<div class="portal-main">
    <!-- Stat Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;" data-stagger>
        <div class="stat-card stat-primary reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">Events Created</span>
                <div class="stat-card-icon"><i class="fa-solid fa-layer-group"></i></div>
            </div>
            <div class="stat-card-value">{{ $totalEvents }}</div>
            <div class="stat-card-sub">Total proposed events</div>
        </div>

        <div class="stat-card stat-success reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">Approved & Live</span>
                <div class="stat-card-icon"><i class="fa-solid fa-circle-check"></i></div>
            </div>
            <div class="stat-card-value">{{ $approvedEventsCount }}</div>
            <div class="stat-card-sub">Active campus events</div>
        </div>

        <div class="stat-card stat-warning reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">Pending Approval</span>
                <div class="stat-card-icon"><i class="fa-solid fa-hourglass-half"></i></div>
            </div>
            <div class="stat-card-value">{{ $pendingEventsCount }}</div>
            <div class="stat-card-sub">Awaiting admin review</div>
        </div>

        <div class="stat-card stat-secondary reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">Total Registrations</span>
                <div class="stat-card-icon"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="stat-card-value">{{ $totalRegistrations }}</div>
            <div class="stat-card-sub">Student bookings across events</div>
        </div>
    </div>

    <!-- Events Table -->
    <div class="card reveal" style="padding: 2rem;">
        <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.6rem;">
            <i class="fa-solid fa-calendar-days" style="color: var(--primary-light); margin-right: 0.4rem;"></i>
            My Events & Registrations
        </h3>

        @if($events->count() > 0)
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Event Title</th>
                            <th>Category</th>
                            <th>Venue & Capacity</th>
                            <th>Start Date</th>
                            <th>Approval Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr>
                                <td>
                                    <strong><a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a></strong>
                                </td>
                                <td><span class="badge badge-primary">{{ $event->category->name }}</span></td>
                                <td>
                                    <div>{{ $event->venue }}</div>
                                    <span style="font-size: 0.78rem; color: var(--text-dim);">{{ $event->capacity - $event->available_slots }} / {{ $event->capacity }} Reserved</span>
                                </td>
                                <td style="white-space: nowrap;">{{ $event->start_date->format('M d, Y • h:i A') }}</td>
                                <td>
                                    @if($event->status === 'approved')
                                        <span class="badge badge-success"><i class="fa-solid fa-check-double"></i> Live Approved</span>
                                    @elseif($event->status === 'pending')
                                        <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Pending Review</span>
                                    @elseif($event->status === 'rejected')
                                        <span class="badge badge-danger"><i class="fa-solid fa-xmark"></i> Rejected</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($event->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                                        <a href="{{ route('organizer.events.registrations', $event->id) }}" class="btn btn-sm btn-secondary" title="View Registrants">
                                            <i class="fa-solid fa-users"></i> Roster
                                        </a>
                                        <a href="{{ route('organizer.events.scanner', $event->id) }}" class="btn btn-sm btn-outline" title="QR Attendance Scanner">
                                            <i class="fa-solid fa-qrcode"></i> QR Scanner
                                        </a>
                                        <a href="{{ route('organizer.events.edit', $event->id) }}" class="btn btn-sm btn-secondary" title="Edit Event">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-calendar-plus empty-state-icon"></i>
                <h3>No events created yet</h3>
                <p>Click <strong>Create New Event Proposal</strong> to submit your first college fest or workshop!</p>
                <a href="{{ route('organizer.events.create') }}" class="btn btn-primary btn-sm" style="margin-top: 1rem;">
                    <i class="fa-solid fa-plus-circle"></i> Create Proposal
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
