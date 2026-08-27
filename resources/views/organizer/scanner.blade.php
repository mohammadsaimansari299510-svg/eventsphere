@extends('layouts.portal')

@section('title', 'QR Attendance Scanner - Organizer Portal')

@section('content')
<div class="portal-topbar">
    <div class="portal-topbar-left">
        <h1 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">
            <i class="fa-solid fa-qrcode" style="color: var(--primary-light); margin-right: 0.4rem;"></i>
            QR Attendance Scanner
        </h1>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
            Event: <strong>{{ $event->title }}</strong>
        </p>
    </div>

    <a href="{{ route('organizer.events.registrations', $event->id) }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to Roster
    </a>
</div>

<div class="portal-main">
    <!-- Scanner / Manual Code Entry -->
    <div class="card reveal" style="padding: 2.5rem; margin-bottom: 2.5rem; max-width: 760px; margin-left: auto; margin-right: auto;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 72px; height: 72px; background: rgba(99, 102, 241, 0.12); border: 2px dashed var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                <i class="fa-solid fa-expand" style="font-size: 1.8rem; color: var(--primary-light);"></i>
            </div>
            <h3 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 0.3rem;">Verify QR Pass Token</h3>
            <p style="color: var(--text-muted); font-size: 0.88rem;">Enter or scan the participant's unique <code>QR-XXXX</code> token for real-time check-in</p>
        </div>

        <form action="{{ route('organizer.events.verify', $event->id) }}" method="POST" style="max-width: 480px; margin: 0 auto;">
            @csrf
            <div class="form-group">
                <label class="form-label required">Enter QR Pass Token</label>
                <div style="display: flex; gap: 0.75rem;">
                    <input type="text" name="qr_token" class="form-control" placeholder="QR-XXXXXXXXXXXX-1" required autofocus style="font-family: monospace; font-size: 1rem; text-transform: uppercase;">
                    <button type="submit" class="btn btn-primary btn-sm" style="white-space: nowrap; padding: 0 1.25rem;">
                        <i class="fa-solid fa-check"></i> Verify
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Checked-In Participants Feed -->
    <div class="card reveal" style="padding: 2rem; max-width: 760px; margin-left: auto; margin-right: auto;">
        <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.6rem;">
            <i class="fa-solid fa-user-check" style="color: var(--success); margin-right: 0.4rem;"></i>
            Today's Checked-In Attendees ({{ $todayAttendances->count() }})
        </h3>

        @if($todayAttendances->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach($todayAttendances as $attendance)
                    <div style="display: flex; justify-content: space-between; align-items: center; background: var(--bg-surface); padding: 0.9rem 1.2rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); flex-wrap: wrap; gap: 0.5rem;">
                        <div>
                            <span style="font-weight: 700; color: var(--text-main); font-size: 0.95rem;">{{ $attendance->user->name }}</span>
                            <span style="font-size: 0.78rem; color: var(--text-dim); margin-left: 0.4rem;">({{ $attendance->user->enrolment_number }} • {{ $attendance->user->department }})</span>
                        </div>
                        <span class="badge badge-success">
                            <i class="fa-regular fa-clock"></i> {{ $attendance->checked_in_at->format('h:i:s A') }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: var(--text-dim); text-align: center; padding: 2rem;">No participants checked in yet today.</p>
        @endif
    </div>
</div>
@endsection
