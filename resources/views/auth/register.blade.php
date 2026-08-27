@extends('layouts.app')

@section('title', 'Create Account - EventSphere')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card register-card reveal">
        
        <!-- Role Promotion Banner -->
        <div class="organizer-promo-banner">
            <div class="organizer-promo-icon">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div class="organizer-promo-text">
                <strong>Organizing a campus event or heading a club?</strong>
                <span>Register as an Organizer to submit proposals, manage registrations, scan QR passes, and issue digital certificates.</span>
            </div>
        </div>

        <div class="auth-header">
            <div class="auth-icon-badge">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h2>Create Your Account</h2>
            <p>Join the centralized campus event ecosystem</p>
        </div>

        <!-- Role Selector Switcher -->
        <div class="role-selector-container">
            <div class="role-switch-btn {{ request('role') === 'organizer' ? '' : 'active' }}" onclick="selectRole('student')" id="btnRoleStudent">
                <i class="fa-solid fa-user-graduate"></i>
                <div>
                    <div class="role-switch-title">Student Participant</div>
                    <div class="role-switch-desc">Browse, register & download passes</div>
                </div>
            </div>
            <div class="role-switch-btn {{ request('role') === 'organizer' ? 'active' : '' }}" onclick="selectRole('organizer')" id="btnRoleOrganizer">
                <i class="fa-solid fa-briefcase"></i>
                <div>
                    <div class="role-switch-title">Faculty / Club Organizer</div>
                    <div class="role-switch-desc">Host events, scan QR & issue certs</div>
                </div>
            </div>
        </div>

        <form action="{{ route('register') }}" method="POST" id="registerForm">
            @csrf
            <input type="hidden" name="role" id="selectedRole" value="{{ request('role', 'student') }}">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required" for="name">
                        <i class="fa-solid fa-id-card" style="color:var(--primary); margin-right:0.3rem;"></i>
                        Full Name
                    </label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Sarah Jenkins" required>
                    @error('name') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required" for="username">
                        <i class="fa-solid fa-at" style="color:var(--primary); margin-right:0.3rem;"></i>
                        Username
                    </label>
                    <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" placeholder="e.g. sarah_jenkins" required>
                    @error('username') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required" for="email">
                        <i class="fa-solid fa-envelope" style="color:var(--primary); margin-right:0.3rem;"></i>
                        Institutional Email Address
                    </label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="e.g. sarah@eventsphere.edu" required>
                    @error('email') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">
                        <i class="fa-solid fa-phone" style="color:var(--primary); margin-right:0.3rem;"></i>
                        Phone Number
                    </label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1 (555) 019-2831">
                    @error('phone') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required" for="department">
                        <i class="fa-solid fa-building-columns" style="color:var(--primary); margin-right:0.3rem;"></i>
                        Academic Department / Faculty
                    </label>
                    <select name="department" id="department" class="form-select" required>
                        <option value="">Select Department</option>
                        <option value="Computer Science & Engineering" {{ old('department') == 'Computer Science & Engineering' ? 'selected' : '' }}>Computer Science & Engineering</option>
                        <option value="Information Technology" {{ old('department') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                        <option value="Electronics & Communication" {{ old('department') == 'Electronics & Communication' ? 'selected' : '' }}>Electronics & Communication</option>
                        <option value="Mechanical Engineering" {{ old('department') == 'Mechanical Engineering' ? 'selected' : '' }}>Mechanical Engineering</option>
                        <option value="Business Administration" {{ old('department') == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                        <option value="Arts & Humanities" {{ old('department') == 'Arts & Humanities' ? 'selected' : '' }}>Arts & Humanities</option>
                        <option value="Student Affairs & Cultural Council" {{ old('department') == 'Student Affairs & Cultural Council' ? 'selected' : '' }}>Student Affairs & Cultural Council</option>
                        <option value="Physical Education & Athletics" {{ old('department') == 'Physical Education & Athletics' ? 'selected' : '' }}>Physical Education & Athletics</option>
                    </select>
                    @error('department') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required" for="enrolment_number" id="idLabel">
                        <i class="fa-solid fa-hashtag" style="color:var(--primary); margin-right:0.3rem;"></i>
                        <span id="idLabelText">Enrolment / Roll Number</span>
                    </label>
                    <input type="text" name="enrolment_number" id="enrolment_number" class="form-control" value="{{ old('enrolment_number') }}" placeholder="e.g. EN20269981 or FAC-CS-042" required>
                    @error('enrolment_number') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required" for="password">
                        <i class="fa-solid fa-lock" style="color:var(--primary); margin-right:0.3rem;"></i>
                        Password
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 8 characters" required>
                        <button type="button" class="input-group-toggle" aria-label="Toggle password visibility">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('password') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label required" for="password_confirmation">
                        <i class="fa-solid fa-lock-open" style="color:var(--primary); margin-right:0.3rem;"></i>
                        Confirm Password
                    </label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Re-enter password" required>
                        <button type="button" class="input-group-toggle" aria-label="Toggle password visibility">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full btn-lg" style="justify-content: center; margin-top: 1rem;" id="submitBtn">
                <i class="fa-solid fa-user-check"></i> Complete Registration
            </button>
        </form>

        <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-muted);">
            Already have an account? <a href="{{ route('login') }}" style="font-weight: 700; color: var(--primary);">Sign In Here</a>
        </p>
    </div>
</div>
@endsection

@section('scripts')
<script>
function selectRole(role) {
    document.getElementById('selectedRole').value = role;
    const btnStudent = document.getElementById('btnRoleStudent');
    const btnOrganizer = document.getElementById('btnRoleOrganizer');
    const idLabelText = document.getElementById('idLabelText');
    const idInput = document.getElementById('enrolment_number');
    const submitBtn = document.getElementById('submitBtn');

    if (role === 'organizer') {
        btnOrganizer.classList.add('active');
        btnStudent.classList.remove('active');
        idLabelText.textContent = 'Faculty Staff ID / Club Head Code';
        idInput.placeholder = 'e.g. FAC-CS-0042';
        submitBtn.innerHTML = '<i class="fa-solid fa-briefcase"></i> Register as Event Organizer';
    } else {
        btnStudent.classList.add('active');
        btnOrganizer.classList.remove('active');
        idLabelText.textContent = 'Student Enrolment / Roll Number';
        idInput.placeholder = 'e.g. EN20269981';
        submitBtn.innerHTML = '<i class="fa-solid fa-user-check"></i> Complete Student Registration';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const currentRole = document.getElementById('selectedRole').value;
    if (currentRole === 'organizer') {
        selectRole('organizer');
    }
});
</script>
@endsection
