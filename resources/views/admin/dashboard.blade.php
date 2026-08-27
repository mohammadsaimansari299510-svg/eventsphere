@extends('layouts.portal')

@section('title', 'System Control Center - Admin Portal')

@section('content')
<div class="portal-topbar">
    <div class="portal-topbar-left">
        <h1 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">System Control Center</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
            Real-time campus metrics, event approvals, user role management & report exports
        </p>
    </div>

    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <button data-modal-target="systemAnnouncementModal" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-bullhorn"></i> Broadcast Announcement
        </button>
        <a href="{{ route('admin.events.pending') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-clock" style="color: var(--warning);"></i> Pending ({{ $pendingEventsCount }})
        </a>
    </div>
</div>

<div class="portal-main">
    <!-- System Metrics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;" data-stagger>
        <div class="stat-card stat-primary reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">Total Users</span>
                <div class="stat-card-icon"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="stat-card-value">{{ $totalUsers }}</div>
            <div class="stat-card-sub">{{ $studentsCount }} Students • {{ $organizersCount }} Organizers</div>
        </div>

        <div class="stat-card stat-secondary reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">Total Events</span>
                <div class="stat-card-icon"><i class="fa-solid fa-calendar-check"></i></div>
            </div>
            <div class="stat-card-value">{{ $totalEvents }}</div>
            <div class="stat-card-sub">{{ $approvedEventsCount }} Approved • {{ $pendingEventsCount }} Pending</div>
        </div>

        <div class="stat-card stat-accent reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">Registrations</span>
                <div class="stat-card-icon"><i class="fa-solid fa-ticket"></i></div>
            </div>
            <div class="stat-card-value">{{ $totalRegistrations }}</div>
            <div class="stat-card-sub">Campus event bookings</div>
        </div>

        <div class="stat-card stat-success reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">Certificates Issued</span>
                <div class="stat-card-icon"><i class="fa-solid fa-certificate"></i></div>
            </div>
            <div class="stat-card-value">{{ $totalCertificates }}</div>
            <div class="stat-card-sub">Verified credentials</div>
        </div>
    </div>

    <!-- Pending Proposals & CSV Export Cards -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 3rem;" class="admin-dashboard-grid">
        <!-- Pending Event Proposals -->
        <div class="card reveal" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.75rem;">
                <h3 style="font-size: 1.2rem; font-weight: 700;">
                    <i class="fa-solid fa-clock" style="color: var(--warning); margin-right: 0.4rem;"></i>
                    Pending Event Proposals
                </h3>
                <a href="{{ route('admin.events.pending') }}" class="btn btn-outline btn-sm">View All Pending</a>
            </div>

            @if($recentPendingEvents->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($recentPendingEvents as $pEvent)
                        <div style="display: flex; justify-content: space-between; align-items: center; background: var(--bg-surface); padding: 1.1rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); flex-wrap: wrap; gap: 1rem;">
                            <div>
                                <strong style="color: var(--text-main); font-size: 1.05rem; display: block;">{{ $pEvent->title }}</strong>
                                <span style="font-size: 0.82rem; color: var(--text-muted);">Organized by {{ $pEvent->organizer->name }} ({{ $pEvent->organizing_department }})</span>
                            </div>

                            <div style="display: flex; gap: 0.5rem;">
                                <form action="{{ route('admin.events.approve', $pEvent->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa-solid fa-check"></i> Approve</button>
                                </form>

                                <button data-modal-target="rejectModal-{{ $pEvent->id }}" class="btn btn-sm btn-danger"><i class="fa-solid fa-xmark"></i> Reject</button>

                                <!-- Reject Reason Modal -->
                                <div id="rejectModal-{{ $pEvent->id }}" class="modal-backdrop">
                                    <div class="modal-card">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Reject Event Proposal</h3>
                                            <button data-modal-close class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
                                        </div>
                                        <form action="{{ route('admin.events.reject', $pEvent->id) }}" method="POST">
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
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--text-muted); text-align: center; padding: 2.5rem;">No pending event proposals awaiting review.</p>
            @endif
        </div>

        <!-- Export System Reports -->
        <div class="card reveal" style="padding: 2rem;">
            <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.75rem;">
                <i class="fa-solid fa-file-export" style="color: var(--accent); margin-right: 0.4rem;"></i>
                System CSV Reports
            </h3>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="{{ route('admin.reports.export', 'participation') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fa-solid fa-file-csv" style="color: var(--success); font-size: 1.25rem;"></i> Export Registrations CSV
                </a>

                <a href="{{ route('admin.reports.export', 'feedback') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fa-solid fa-file-csv" style="color: var(--warning); font-size: 1.25rem;"></i> Export Feedback CSV
                </a>

                <a href="{{ route('admin.reports.export', 'certificates') }}" class="btn btn-secondary" style="justify-content: flex-start;">
                    <i class="fa-solid fa-file-csv" style="color: var(--secondary); font-size: 1.25rem;"></i> Export Certificates CSV
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Broadcast Announcement Modal -->
<div id="systemAnnouncementModal" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-bullhorn" style="color: var(--primary-light);"></i> Broadcast Announcement</h3>
            <button data-modal-close class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('admin.announcements.send') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label required">Announcement Title</label>
                <input type="text" name="title" class="form-control" placeholder="Annual Campus Fest Schedule Announcement" required>
            </div>

            <div class="form-group">
                <label class="form-label required">Target Audience Role</label>
                <select name="target_role" class="form-select" required>
                    <option value="all">All Registered Users (Students & Faculty)</option>
                    <option value="student">Students Only</option>
                    <option value="organizer">Organizers Only</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label required">Message Content</label>
                <textarea name="message" class="form-textarea" rows="4" placeholder="Broadcast message content..." required></textarea>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" data-modal-close class="btn btn-secondary btn-sm">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane"></i> Broadcast Message</button>
            </div>
        </form>
    </div>
</div>

<style>
@media (max-width: 992px) {
    .admin-dashboard-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
