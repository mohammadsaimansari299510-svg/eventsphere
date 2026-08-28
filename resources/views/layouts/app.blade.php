<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EVENTSPHERE - Centralized College Event Discovery & Management Platform">
    <title>@yield('title', 'EVENTSPHERE - College Event Management')</title>

    <!-- Preconnect for Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="{{ asset('css/eventsphere.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @yield('styles')
</head>
<body class="page-load">

    <!-- ================================================
         NAVIGATION BAR (MODERN DARK GLASSMORPHISM)
         ================================================ -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">

            <!-- Brand Logo (EVENTSPHERE - UPPERCASE) -->
            <a href="{{ route('home') }}" class="brand-logo">
                <div class="logo-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <span>EVENTSPHERE</span>
            </a>

            <!-- Desktop Nav Links -->
            <ul class="nav-links">
                <li>
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i> Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-days"></i> Events
                    </a>
                </li>
                <li>
                    <a href="{{ route('gallery.index') }}" class="nav-link {{ request()->routeIs('gallery.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-photo-film"></i> Gallery
                    </a>
                </li>
                <li>
                    <a href="{{ route('register') }}?role=organizer" class="nav-link {{ request()->fullUrlIs('*role=organizer*') ? 'active' : '' }}">
                        <i class="fa-solid fa-briefcase"></i> Organizers
                    </a>
                </li>
                <li>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-info"></i> About
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <i class="fa-solid fa-envelope"></i> Contact
                    </a>
                </li>
                <li>
                    <a href="{{ route('faq') }}" class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-question"></i> FAQs
                    </a>
                </li>
            </ul>

            <!-- Desktop Nav Actions -->
            <div class="nav-actions">
                @auth
                    <div class="user-badge">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <span class="role-pill role-{{ Auth::user()->role }}">{{ Auth::user()->role }}</span>
                        <span style="font-weight:700; font-size:0.88rem; color:#FFFFFF;">{{ Str::words(Auth::user()->name, 1, '') }}</span>
                    </div>

                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-shield-halved"></i> Admin Portal
                        </a>
                    @elseif(Auth::user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-briefcase"></i> Organizer Portal
                        </a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-user-graduate"></i> My Dashboard
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm" title="Sign Out">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Log In
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-user-plus"></i> Sign Up
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu" id="mobileMenu" role="dialog" aria-modal="true">
        <ul class="mobile-nav-links">
            <li>
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Home
                </a>
            </li>
            <li>
                <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days"></i> Events
                </a>
            </li>
            <li>
                <a href="{{ route('gallery.index') }}" class="nav-link {{ request()->routeIs('gallery.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-photo-film"></i> Gallery
                </a>
            </li>
            <li>
                <a href="{{ route('register') }}?role=organizer" class="nav-link">
                    <i class="fa-solid fa-briefcase"></i> Organizers
                </a>
            </li>
            <li>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-info"></i> About
                </a>
            </li>
            <li>
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                    <i class="fa-solid fa-envelope"></i> Contact
                </a>
            </li>
            <li>
                <a href="{{ route('faq') }}" class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-question"></i> FAQs
                </a>
            </li>
        </ul>

        <div class="mobile-nav-actions">
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary w-full">
                        <i class="fa-solid fa-shield-halved"></i> Admin Portal
                    </a>
                @elseif(Auth::user()->isOrganizer())
                    <a href="{{ route('organizer.dashboard') }}" class="btn btn-primary w-full">
                        <i class="fa-solid fa-briefcase"></i> Organizer Portal
                    </a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="btn btn-primary w-full">
                        <i class="fa-solid fa-user-graduate"></i> My Dashboard
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline w-full" style="margin-top:0.5rem;">
                        <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline w-full">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Log In
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary w-full">
                    <i class="fa-solid fa-user-plus"></i> Student Sign Up
                </a>
                <a href="{{ route('register') }}?role=organizer" class="btn btn-primary-light w-full">
                    <i class="fa-solid fa-briefcase"></i> Organizer Sign Up
                </a>
            @endauth
        </div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">

        <!-- Flash Messages -->
        @if(session('success'))
            <div style="margin: 1.5rem 2rem 0; padding: 0;">
                <div class="alert alert-success alert-auto-dismiss">
                    <span><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</span>
                    <button class="alert-close"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div style="margin: 1.5rem 2rem 0; padding: 0;">
                <div class="alert alert-error alert-auto-dismiss">
                    <span><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</span>
                    <button class="alert-close"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div style="margin: 1.5rem 2rem 0; padding: 0;">
                <div class="alert alert-warning alert-auto-dismiss">
                    <span><i class="fa-solid fa-circle-exclamation"></i> {{ session('warning') }}</span>
                    <button class="alert-close"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div style="margin: 1.5rem 2rem 0; padding: 0;">
                <div class="alert alert-info alert-auto-dismiss">
                    <span><i class="fa-solid fa-circle-info"></i> {{ session('info') }}</span>
                    <button class="alert-close"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- ================================================
         DARK THEME FOOTER
         ================================================ -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Brand Column -->
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="brand-logo" style="margin-bottom:0.75rem;">
                    <div class="logo-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <span>EVENTSPHERE</span>
                </a>
                <p>The centralized campus event management, registration, and credentialing ecosystem connecting students, faculty organizers, and university administration.</p>
                <div class="social-links" style="margin-top:1.25rem;">
                    <a href="#" class="social-link" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" class="social-link" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="social-link" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" class="social-link" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('events.index') }}">Browse Events</a></li>
                    <li><a href="{{ route('gallery.index') }}">Media Gallery</a></li>
                    <li><a href="{{ route('register') }}?role=organizer">Host an Event</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact Support</a></li>
                    <li><a href="{{ route('faq') }}">FAQs</a></li>
                </ul>
            </div>

            <!-- Event Categories -->
            <div class="footer-col">
                <h4>Event Categories</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('events.index') }}">Cultural Carnivals</a></li>
                    <li><a href="{{ route('events.index') }}">Technical Fests & Hackathons</a></li>
                    <li><a href="{{ route('events.index') }}">Sports Tournaments</a></li>
                    <li><a href="{{ route('events.index') }}">Workshops & Seminars</a></li>
                    <li><a href="{{ route('events.index') }}">Annual Day Functions</a></li>
                    <li><a href="{{ route('events.index') }}">Intercollegiate Meets</a></li>
                </ul>
            </div>

            <!-- Support & Access -->
            <div class="footer-col">
                <h4>Support & Access</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('faq') }}">FAQ Center</a></li>
                    <li><a href="{{ route('contact') }}">Help Desk</a></li>
                    @guest
                    <li><a href="{{ route('login') }}">Sign In</a></li>
                    <li><a href="{{ route('register') }}">Student Registration</a></li>
                    <li><a href="{{ route('register') }}?role=organizer">Organizer Registration</a></li>
                    @endguest
                </ul>
                <div style="margin-top:1.25rem;">
                    <div style="font-size:0.85rem; color:var(--text-muted); margin-bottom:0.5rem;">
                        <i class="fa-solid fa-envelope" style="color:var(--primary-light); margin-right:0.4rem;"></i>
                        support@eventsphere.edu
                    </div>
                    <div style="font-size:0.85rem; color:var(--text-muted);">
                        <i class="fa-solid fa-phone" style="color:var(--primary-light); margin-right:0.4rem;"></i>
                        +1 (800) 555-EVENT
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} EVENTSPHERE. All rights reserved.</span>
            <span style="color:var(--text-dim);">Built for campus communities.</span>
        </div>
    </footer>

    <!-- Lightbox Modal -->
    <div class="lightbox" id="lightbox">
        <div class="lightbox-content">
            <img id="lightboxImg" src="" alt="Gallery image" style="display:none;">
            <video id="lightboxVideo" src="" controls style="display:none; max-width:90vw; max-height:85vh;"></video>
        </div>
        <button class="lightbox-close" id="lightboxClose" aria-label="Close lightbox">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('js/eventsphere.js') }}"></script>
    @yield('scripts')
</body>
</html>
