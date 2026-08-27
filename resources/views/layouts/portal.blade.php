<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EVENTSPHERE - Management Portal">
    <title>@yield('title', 'EVENTSPHERE Management Portal')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="{{ asset('css/eventsphere.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
</head>
<body>

<div class="portal-layout">

    <!-- ================================================
         SIDEBAR (DARK THEME)
         ================================================ -->
    <aside class="portal-sidebar" id="portalSidebar">

        <!-- Sidebar Header / Brand -->
        <div class="sidebar-header">
            <a href="{{ route('home') }}" class="sidebar-brand">
                <div class="logo-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <div class="sidebar-brand-name">EVENTSPHERE</div>
                </div>
            </a>
            <div class="sidebar-role-tag" style="margin-top:0.35rem; padding-left:0.35rem;">
                {{ Auth::user()->isAdmin() ? 'Administrator Portal' : 'Organizer Portal' }}
            </div>
        </div>

        <!-- Sidebar User -->
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                <span class="role-pill role-{{ Auth::user()->role }}" style="font-size:0.65rem;">
                    {{ Auth::user()->role }}
                </span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <ul style="list-style:none;">

                @if(Auth::user()->isAdmin())
                    <li class="sidebar-section-label">Admin Controls</li>

                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie" style="color:#60A5FA;"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.pending') }}"
                           class="sidebar-link {{ request()->routeIs('admin.events.pending') ? 'active' : '' }}">
                            <i class="fa-solid fa-clock" style="color:#FBBF24;"></i>
                            Pending Proposals
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}"
                           class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <i class="fa-solid fa-users-gear" style="color:#22D3EE;"></i>
                            User Management
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.content') }}"
                           class="sidebar-link {{ request()->routeIs('admin.content') ? 'active' : '' }}">
                            <i class="fa-solid fa-filter-circle-xmark" style="color:#A78BFA;"></i>
                            Moderation Center
                        </a>
                    </li>

                    <li class="sidebar-section-label">Reports & Exports</li>

                    <li>
                        <a href="{{ route('admin.reports.export', 'participation') }}" class="sidebar-link">
                            <i class="fa-solid fa-file-csv" style="color:#34D399;"></i>
                            Export Registrations
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.export', 'feedback') }}" class="sidebar-link">
                            <i class="fa-solid fa-file-csv" style="color:#FBBF24;"></i>
                            Export Feedback
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.export', 'certificates') }}" class="sidebar-link">
                            <i class="fa-solid fa-file-csv" style="color:#A78BFA;"></i>
                            Export Certificates
                        </a>
                    </li>

                @elseif(Auth::user()->isOrganizer())
                    <li class="sidebar-section-label">Organizer Menu</li>

                    <li>
                        <a href="{{ route('organizer.dashboard') }}"
                           class="sidebar-link {{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-gauge-high" style="color:#60A5FA;"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('organizer.events.create') }}"
                           class="sidebar-link {{ request()->routeIs('organizer.events.create') ? 'active' : '' }}">
                            <i class="fa-solid fa-plus-circle" style="color:#34D399;"></i>
                            Create Event
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gallery.index') }}" class="sidebar-link">
                            <i class="fa-solid fa-images" style="color:#22D3EE;"></i>
                            Media Gallery
                        </a>
                    </li>
                @endif

                <li class="sidebar-section-label">Navigation</li>

                <li>
                    <a href="{{ route('home') }}" class="sidebar-link">
                        <i class="fa-solid fa-globe" style="color:var(--text-dim);"></i>
                        Public Website
                    </a>
                </li>
                <li>
                    <a href="{{ route('events.index') }}" class="sidebar-link">
                        <i class="fa-solid fa-calendar-days" style="color:var(--text-dim);"></i>
                        All Events
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm w-full" style="justify-content:center;">
                    <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile sidebar overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ================================================
         MAIN CONTENT AREA
         ================================================ -->
    <main class="portal-content">

        <!-- Mobile Header (visible < 1024px) -->
        <div class="portal-mobile-header">
            <a href="{{ route('home') }}" class="brand-logo" style="font-size:1.15rem;">
                <div class="logo-icon" style="width:30px; height:30px; font-size:0.85rem;">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <span>EVENTSPHERE</span>
            </a>

            <button class="sidebar-mobile-toggle" id="sidebarToggle" aria-label="Open menu">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Flash Messages -->
        <div style="padding: 1.5rem 2.5rem 0;">
            @if(session('success'))
                <div class="alert alert-success alert-auto-dismiss">
                    <span><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</span>
                    <button class="alert-close"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error alert-auto-dismiss">
                    <span><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</span>
                    <button class="alert-close"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-auto-dismiss">
                    <span><i class="fa-solid fa-circle-exclamation"></i> {{ session('warning') }}</span>
                    <button class="alert-close"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>
</div>

<!-- JavaScript -->
<script src="{{ asset('js/eventsphere.js') }}"></script>
@yield('scripts')
</body>
</html>
