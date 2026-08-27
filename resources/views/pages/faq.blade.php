@extends('layouts.app')

@section('title', 'Frequently Asked Questions - EventSphere')

@section('content')
<div style="max-width: 860px; margin: 2rem auto;">
    <div style="text-align: center; margin-bottom: 3rem;" class="reveal">
        <span class="section-label">Common Questions</span>
        <h1 class="page-title" style="font-size: clamp(2rem, 4vw, 2.5rem); margin-top: 0.5rem; margin-bottom: 0.75rem;">Frequently Asked Questions</h1>
        <p class="page-subtitle" style="margin: 0 auto; max-width: 600px;">Find quick answers regarding event browsing, online registration, QR check-in passes, waitlists, and digital certificates.</p>
    </div>

    <div class="accordion reveal">
        <div class="accordion-item open">
            <button class="accordion-trigger" type="button">
                <span>Can I browse and explore college events without creating an account?</span>
                <i class="fa-solid fa-chevron-down accordion-icon"></i>
            </button>
            <div class="accordion-body" style="height: auto;">
                <div class="accordion-content">
                    Yes! Unregistered visitors can freely browse all upcoming, ongoing, and past events, view category filters, read event details, rulebooks, and explore media gallery uploads without logging in. You will only be prompted to log in or sign up when attempting actions such as registering for an event, submitting feedback, or downloading certificates.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <button class="accordion-trigger" type="button">
                <span>How does the Automatic Waitlist Promotion work?</span>
                <i class="fa-solid fa-chevron-down accordion-icon"></i>
            </button>
            <div class="accordion-body">
                <div class="accordion-content">
                    When an event reaches its venue capacity, additional registrants are automatically placed on a waitlist with position numbers (#1, #2, etc.). If a registered student cancels their registration prior to the cutoff date, the highest-priority waitlisted student is automatically promoted to registered status and receives an instant notification with their QR pass.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <button class="accordion-trigger" type="button">
                <span>How do I check in at the venue on event day?</span>
                <i class="fa-solid fa-chevron-down accordion-icon"></i>
            </button>
            <div class="accordion-body">
                <div class="accordion-content">
                    Log into your student dashboard and click <strong>"View QR Pass"</strong> under your registered event. Present the generated QR token on your mobile screen to event organizers at the entry gate, who will scan it using the Organizer Attendance Scanner tool to verify your attendance instantly.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <button class="accordion-trigger" type="button">
                <span>When and where can I download my participation e-certificate?</span>
                <i class="fa-solid fa-chevron-down accordion-icon"></i>
            </button>
            <div class="accordion-body">
                <div class="accordion-content">
                    E-certificates are issued after the event concludes for participants whose attendance was verified on-site by organizers. Once issued, certificates can be previewed, printed, and downloaded in high resolution directly from the "Certificates" section in your student dashboard.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <button class="accordion-trigger" type="button">
                <span>How do faculty members or clubs get organizer privileges?</span>
                <i class="fa-solid fa-chevron-down accordion-icon"></i>
            </button>
            <div class="accordion-body">
                <div class="accordion-content">
                    Faculty coordinators and club heads can request organizer access from the System Administrator. Once granted, organizers gain access to the Organizer Portal to propose events, review registered student lists, scan attendee QR codes, upload photos/videos, and issue certificates.
                </div>
            </div>
        </div>
    </div>

    <!-- Need more help card -->
    <div class="card reveal" style="padding: 2rem; text-align: center; margin-top: 3rem; background: var(--bg-card); border: 1px solid var(--border-color);">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">Still have questions?</h3>
        <p style="color: var(--text-muted); font-size: 0.92rem; margin-bottom: 1.25rem;">Our campus support team is ready to assist you with any inquiries.</p>
        <a href="{{ route('contact') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-envelope"></i> Contact Support Team</a>
    </div>
</div>
@endsection
