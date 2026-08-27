@extends('layouts.portal')

@section('title', 'User Management - Admin Portal')

@section('content')
<div class="portal-topbar">
    <div class="portal-topbar-left">
        <h1 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">
            <i class="fa-solid fa-users-gear" style="color: var(--accent); margin-right: 0.4rem;"></i>
            User Management
        </h1>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
            View accounts, assign roles, suspend/activate accounts & reset passwords
        </p>
    </div>
</div>

<div class="portal-main">
    <!-- Search & Role Filters -->
    <div class="card reveal" style="padding: 1.25rem; margin-bottom: 2rem;">
        <form action="{{ route('admin.users') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, enrolment, username..." value="{{ request('search') }}">
            </div>
            <div style="width: 180px;">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="organizer" {{ request('role') == 'organizer' ? 'selected' : '' }}>Organizer</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    <!-- Users Table -->
    <div class="card reveal" style="padding: 2rem;">
        @if($users->count() > 0)
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User Name & Email</th>
                            <th>Enrolment / ID</th>
                            <th>Department</th>
                            <th>Current Role</th>
                            <th>Account Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--text-dim);">{{ $user->email }} ({{ $user->username }})</div>
                                </td>
                                <td>{{ $user->enrolment_number ?? '-' }}</td>
                                <td>{{ $user->department ?? 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('admin.users.role', $user->id) }}" method="POST" style="display: flex; gap: 0.3rem;">
                                        @csrf
                                        <select name="role" class="form-select" style="padding: 0.3rem 0.6rem; font-size: 0.8rem; width: auto;" onchange="this.form.submit()">
                                            <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                                            <option value="organizer" {{ $user->role === 'organizer' ? 'selected' : '' }}>Organizer</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    @if($user->status === 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.4rem;">
                                        <form action="{{ route('admin.users.status', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-danger' : 'btn-secondary' }}" title="Toggle Status">
                                                {{ $user->status === 'active' ? 'Suspend' : 'Activate' }}
                                            </button>
                                        </form>

                                        <button data-modal-target="resetPwdModal-{{ $user->id }}" class="btn btn-sm btn-secondary" title="Reset Password">
                                            <i class="fa-solid fa-key"></i>
                                        </button>
                                    </div>

                                    <!-- Password Reset Modal -->
                                    <div id="resetPwdModal-{{ $user->id }}" class="modal-backdrop">
                                        <div class="modal-card">
                                            <div class="modal-header">
                                                <h3 class="modal-title">Reset Password for {{ $user->name }}</h3>
                                                <button data-modal-close class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            <form action="{{ route('admin.users.password', $user->id) }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="form-label required">New Password</label>
                                                    <input type="password" name="password" class="form-control" required minlength="8">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label required">Confirm Password</label>
                                                    <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                                                </div>
                                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1.5rem;">
                                                    <button type="button" data-modal-close class="btn btn-secondary btn-sm">Cancel</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Save New Password</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 2rem;">
                {{ $users->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-users-slash empty-state-icon"></i>
                <h3>No user accounts found</h3>
                <p>Try clearing your search query or filters.</p>
            </div>
        @endif
    </div>
</div>
@endsection
