@extends('layouts.portal')

@section('title', 'Edit Event - Organizer Portal')

@section('content')
<div class="portal-topbar">
    <div class="portal-topbar-left">
        <h1 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">
            <i class="fa-solid fa-pen-to-square" style="color: var(--secondary); margin-right: 0.4rem;"></i>
            Edit Event Details
        </h1>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
            Updating event schedules or venues will be visible live to all registered participants
        </p>
    </div>
</div>

<div class="portal-main">
    <div class="card reveal" style="padding: 2.5rem; max-width: 900px;">
        <form action="{{ route('organizer.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label required">Event Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                @error('title') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Category</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label required">Organizing Department</label>
                    <input type="text" name="organizing_department" class="form-control" value="{{ old('organizing_department', $event->organizing_department) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label required">Event Description & Rules</label>
                <textarea name="description" class="form-textarea" rows="5" required>{{ old('description', $event->description) }}</textarea>
                @error('description') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Venue / Hall Location</label>
                    <input type="text" name="venue" class="form-control" value="{{ old('venue', $event->venue) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Maximum Seat Limit / Capacity</label>
                    <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $event->capacity) }}" min="1" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label required">Start Date & Time</label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">End Date & Time</label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Registration Cutoff</label>
                    <input type="datetime-local" name="registration_deadline" class="form-control" value="{{ old('registration_deadline', $event->registration_deadline->format('Y-m-d\TH:i')) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Hashtags / Promotional Tags</label>
                <input type="text" name="hashtags" class="form-control" value="{{ old('hashtags', $event->hashtags) }}">
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 2rem;">
                <a href="{{ route('organizer.dashboard') }}" class="btn btn-secondary btn-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk"></i> Update Event</button>
            </div>
        </form>
    </div>
</div>
@endsection
