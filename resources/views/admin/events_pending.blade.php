@extends('layouts.portal')

@section('title', 'Pending Event Proposals - Admin Portal')

@section('content')
<div class="portal-topbar">
    <div class="portal-topbar-left">
        <h1 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">
            <i class="fa-solid fa-clock" style="color: var(--warning); margin-right: 0.4rem;"></i>
            Pending Event Proposals
        </h1>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
            Review event proposals submitted by college societies and faculty before publishing live
        </p>
    </div>
</div>

<div class="portal-main">
    <div class="card reveal" style="padding: 2rem;">
        @if($pendingEvents->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                @foreach($pendingEvents as $event)
                    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
                            <div>
                                <span class="badge badge-primary" style="margin-bottom: 0.5rem;">{{ $event->category->name }}</span>
                                <h3 style="font-size: 1.35rem; font-weight: 700; margin-top: 0.4rem;">{{ $event->title }}</h3>
                                <p style="font-size: 0.85rem; color: var(--text-muted);">Organized by {{ $event->organizer->name }} ({{ $event->organizing_department }})</p>
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <form action="{{ route('admin.events.approve', $event->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-check"></i> Approve & Publish</button>
                                </form>

                                <button data-modal-target="rejectModal-{{ $event->id }}" class="btn btn-danger btn-sm"><i class="fa-solid fa-xmark"></i> Reject</button>
                            </div>
                        </div>

                        <p style="color: var(--text-main); font-size: 0.95rem; line-height: 1.7; margin-bottom: 1.25rem;">
                            {{ $event->description }}
                        </p>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; background: var(--bg-card); padding: 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; border: 1px solid var(--border-subtle);">
                            <div><strong style="color: var(--text-dim); display: block; font-size: 0.75rem; text-transform: uppercase;">Venue</strong> {{ $event->venue }}</div>
                            <div><strong style="color: var(--text-dim); display: block; font-size: 0.75rem; text-transform: uppercase;">Capacity</strong> {{ $event->capacity }} Seats</div>
                            <div><strong style="color: var(--text-dim); display: block; font-size: 0.75rem; text-transform: uppercase;">Start Date</strong> {{ $event->start_date->format('M d, Y • h:i A') }}</div>
                            <div><strong style="color: var(--text-dim); display: block; font-size: 0.75rem; text-transform: uppercase;">Deadline</strong> {{ $event->registration_deadline->format('M d, Y • h:i A') }}</div>
                        </div>

                        <!-- Reject Reason Modal -->
                        <div id="rejectModal-{{ $event->id }}" class="modal-backdrop">
                            <div class="modal-card">
                                <div class="modal-header">
                                    <h3 class="modal-title">Reject Event Proposal</h3>
                                    <button data-modal-close class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <form action="{{ route('admin.events.reject', $event->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label required">Rejection Reason / Feedback</label>
                                        <textarea name="rejection_reason" class="form-textarea" rows="3" required placeholder="Specify why proposal was rejected..."></textarea>
                                    </div>
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                        <button type="button" data-modal-close class="btn btn-secondary btn-sm">Cancel</button>
                                        <button type="submit" class="btn btn-danger btn-sm">Confirm Reject</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-circle-check empty-state-icon" style="color: #4ADE80;"></i>
                <h3>All Clear! No pending proposals awaiting review.</h3>
                <p>New event submissions from organizers will appear here automatically.</p>
            </div>
        @endif
    </div>
</div>
@endsection
