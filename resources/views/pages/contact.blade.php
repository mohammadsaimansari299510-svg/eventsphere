@extends('layouts.app')

@section('title', 'Contact Support - EventSphere')

@section('content')
<div style="max-width: 960px; margin: 2rem auto;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;" class="contact-grid">
        <div class="reveal">
            <span class="section-label">Get In Touch</span>
            <h1 class="page-title" style="font-size: 2.25rem; margin-top: 0.4rem; margin-bottom: 1rem;">Contact EventSphere Support</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 2rem;">
                Have questions regarding event registrations, certificate issuance, technical issues, or faculty organizer access? Fill out the inquiry form or reach out directly to the campus technical committee.
            </p>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem; background: var(--bg-card); padding: 1.1rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <div style="width: 42px; height: 42px; border-radius: var(--radius-sm); background: rgba(99,102,241,0.12); display: flex; align-items: center; justify-content: center; color: var(--primary-light); font-size: 1.2rem; flex-shrink: 0;">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <strong style="color: var(--text-main); display: block; font-size: 0.95rem;">Campus Address</strong>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">Main Administrative Building, Room 204</span>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem; background: var(--bg-card); padding: 1.1rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <div style="width: 42px; height: 42px; border-radius: var(--radius-sm); background: rgba(6,182,212,0.12); display: flex; align-items: center; justify-content: center; color: #22D3EE; font-size: 1.2rem; flex-shrink: 0;">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div>
                        <strong style="color: var(--text-main); display: block; font-size: 0.95rem;">Email Support</strong>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">support@eventsphere.edu</span>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem; background: var(--bg-card); padding: 1.1rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <div style="width: 42px; height: 42px; border-radius: var(--radius-sm); background: rgba(34,197,94,0.12); display: flex; align-items: center; justify-content: center; color: #4ADE80; font-size: 1.2rem; flex-shrink: 0;">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div>
                        <strong style="color: var(--text-main); display: block; font-size: 0.95rem;">Help Desk Line</strong>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">+1 (800) 555-EVENT</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card reveal" style="padding: 2.25rem;">
            <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.6rem;">Send an Inquiry</h3>

            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label required">Your Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Alex Rivera" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="alex@eventsphere.edu" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Subject</label>
                    <input type="text" name="subject" class="form-control" placeholder="Inquiry regarding Technical Fest registration" required>
                </div>

                <div class="form-group">
                    <label class="form-label required">Message</label>
                    <textarea name="message" class="form-textarea" rows="4" placeholder="How can we assist you with EventSphere?" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-full" style="margin-top: 0.5rem;">
                    <i class="fa-solid fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
}
</style>
@endsection
