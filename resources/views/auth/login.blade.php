@extends('layouts.app')

@section('title', 'Sign In - EventSphere')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card reveal">
        <div class="auth-header">
            <div class="auth-icon-badge">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h2>Welcome Back</h2>
            <p>Sign in to access your EventSphere student, organizer, or admin portal</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="login">
                    <i class="fa-solid fa-user" style="color:var(--primary); margin-right:0.3rem;"></i>
                    Email Address or Username
                </label>
                <input type="text" name="login" id="login" class="form-control" value="{{ old('login') }}" placeholder="e.g. your_email@eventsphere.edu" required autofocus>
                @error('login')
                    <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">
                    <i class="fa-solid fa-key" style="color:var(--primary); margin-right:0.3rem;"></i>
                    Password
                </label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                    <button type="button" class="input-group-toggle" aria-label="Toggle password visibility">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <label class="form-check" style="font-size: 0.88rem; color: var(--text-muted); cursor: pointer;">
                    <input type="checkbox" name="remember"> Keep me signed in
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-full btn-lg" style="justify-content: center;">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In to Account
            </button>
        </form>

        <div class="auth-footer-prompt">
            <p>Don't have an account yet?</p>
            <div style="display: flex; gap: 0.75rem; justify-content: center; margin-top: 0.5rem;">
                <a href="{{ route('register') }}" class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-user-plus"></i> Student Sign Up
                </a>
                <a href="{{ route('register') }}?role=organizer" class="btn btn-primary-light btn-sm">
                    <i class="fa-solid fa-briefcase"></i> Organizer Sign Up
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
