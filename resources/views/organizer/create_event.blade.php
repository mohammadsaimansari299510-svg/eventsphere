@extends('layouts.portal')

@section('title', 'Create Event Proposal - Organizer Portal')

@section('content')
<div class="portal-topbar">
    <div class="portal-topbar-left">
        <h1 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">
            <i class="fa-solid fa-calendar-plus" style="color: var(--success); margin-right: 0.4rem;"></i>
            Create Event Proposal
        </h1>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
            Submit proposal for admin approval before publishing on the campus portal
        </p>
    </div>
</div>

<div class="portal-main">
    <div class="card reveal" style="padding: 2.5rem; max-width: 900px;">
        <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label required">Event Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. CodeStorm 2026 Hackathon" required>
                @error('title') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label required">Organizing Department</label>
                    <input type="text" name="organizing_department" class="form-control" value="{{ old('organizing_department', Auth::user()->department) }}" placeholder="Computer Science & Engineering" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label required">Event Description & Rules</label>
                <textarea name="description" class="form-textarea" rows="5" placeholder="Provide full details regarding schedule, eligibility, prize breakdown, guidelines, etc." required>{{ old('description') }}</textarea>
                @error('description') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Venue / Hall Location</label>
                    <input type="text" name="venue" class="form-control" value="{{ old('venue') }}" placeholder="Auditorium Hall B / Tech Lab 3" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Maximum Seat Limit / Capacity</label>
                    <input type="number" name="capacity" class="form-control" value="{{ old('capacity', 100) }}" min="1" required>
                    <span class="form-hint">Enforces seat limits and triggers automated waitlist promotion when full.</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label required">Start Date & Time</label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">End Date & Time</label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Registration Cutoff</label>
                    <input type="datetime-local" name="registration_deadline" class="form-control" value="{{ old('registration_deadline') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Hashtags / Promotional Tags</label>
                <input type="text" name="hashtags" class="form-control" value="{{ old('hashtags') }}" placeholder="#Hackathon2026 #EventSphere #CodingFest">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Banner Image (JPG, PNG, WEBP)</label>
                    <input type="file" name="banner_image" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Official Rulebook PDF</label>
                    <input type="file" name="rulebook_file" class="form-control">
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 2rem;">
                <a href="{{ route('organizer.dashboard') }}" class="btn btn-secondary btn-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane"></i> Submit Proposal</button>
            </div>
        </form>
    </div>
</div>
@endsection
