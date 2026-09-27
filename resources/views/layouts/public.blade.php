<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CampusRide — Campus Mobility Services')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/campusride-logo.png') }}">
    
    <!-- Bootstrap 5.3.3 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --cr-primary: #064E3B;
            --cr-primary-hover: #022C22;
            --cr-secondary: #16A34A;
            --cr-accent: #F4B400;
            --cr-light: #F5F8F6;
            --cr-dark: #17211D;
            --cr-surface: #FFFFFF;
            --cr-muted: #5B6B63;
            --cr-border: rgba(6, 78, 59, 0.12);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--cr-light);
            color: var(--cr-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-campus {
            background-color: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(6, 78, 59, 0.08);
            padding: 0.9rem 0;
            transition: all 0.2s ease;
        }

        .navbar-brand-badge {
            background: linear-gradient(135deg, var(--cr-primary) 0%, var(--cr-secondary) 100%);
            color: #ffffff;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.25);
        }

        .nav-link {
            font-weight: 600;
            color: var(--cr-dark);
            padding: 0.7rem 1rem !important;
            border-radius: 999px;
            transition: all 0.15s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--cr-primary);
            background-color: rgba(22, 163, 74, 0.08);
        }

        .btn-cr-primary {
            background: linear-gradient(135deg, var(--cr-primary) 0%, var(--cr-secondary) 100%);
            color: #ffffff;
            font-weight: 700;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-cr-primary:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(6, 78, 59, 0.22);
        }

        .btn-cr-accent {
            background: linear-gradient(135deg, var(--cr-accent) 0%, #E7A200 100%);
            color: var(--cr-dark);
            font-weight: 700;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-cr-accent:hover {
            color: var(--cr-dark);
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(244, 180, 0, 0.25);
        }

        .footer-campus {
            background: linear-gradient(180deg, #0f1f1a 0%, #0a1714 100%);
            color: #CED4DA;
            margin-top: auto;
            padding: 4rem 0 2rem;
        }

        .footer-campus a {
            color: #DEE2E6;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .footer-campus a:hover {
            color: var(--cr-accent);
        }

        .campus-card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid #DEE2E6;
        }

        .campus-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px -4px rgba(15, 81, 50, 0.12);
        }

        .text-primary, .text-info { color: var(--cr-primary) !important; }
        .bg-primary { background-color: var(--cr-primary) !important; }
        .bg-info { background-color: var(--cr-accent) !important; }
        .text-warning { color: #B58100 !important; }

        .campusride-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            color: var(--cr-primary);
            text-decoration: none;
        }

        .campusride-logo-mark {
            width: 2.8rem;
            height: 2.8rem;
            flex: 0 0 auto;
        }

        .campusride-wordmark {
            display: inline-flex;
            flex-direction: column;
            line-height: 1;
        }

        .campusride-name {
            color: var(--cr-primary);
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .campusride-name span { color: var(--cr-secondary); }

        .campusride-tagline {
            color: #6C757D;
            font-size: 0.48rem;
            font-weight: 800;
            letter-spacing: 0.23em;
            margin-top: 0.32rem;
        }
    </style>
    @yield('styles')
</head>
<body>
    @php
        $publicContactEmail = \App\Models\AppSetting::getValue('contact_email', 'dispatch@campusride.edu');
        $publicContactPhone = \App\Models\AppSetting::getValue('contact_phone', '(555) CAMPUS-RIDE');
        $publicOperatingHours = \App\Models\AppSetting::getValue('operating_hours', 'Day Shuttle: Mon - Fri, 6:30 AM - 8:00 PM | Night Owl: Daily, 8:00 PM - 3:30 AM');
    @endphp

    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-campus sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <x-campusride-logo />
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold text-primary' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active fw-bold text-primary' : '' }}" href="{{ route('about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services') ? 'active fw-bold text-primary' : '' }}" href="{{ route('services') }}">Mobility Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('driver.apply') ? 'active fw-bold text-primary' : '' }}" href="{{ route('driver.apply') }}">
                            <i class="bi bi-car-front me-1"></i> Drive With Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active fw-bold text-primary' : '' }}" href="{{ route('contact') }}">Contact & Help</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        @php
                            $dashboardRoute = match (auth()->user()->role) {
                                'admin'  => route('admin.dashboard'),
                                'driver' => route('driver.dashboard'),
                                default  => route('passenger.dashboard'),
                            };
                        @endphp
                        <a href="{{ $dashboardRoute }}" class="btn btn-cr-primary btn-sm d-flex align-items-center gap-2">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard ({{ auth()->user()->name }})</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Log Out">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm px-3 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Log In
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-cr-accent btn-sm px-3 dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-plus-fill me-1"></i> Create Account
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                <li>
                                    <a class="dropdown-item py-2 small fw-semibold" href="{{ route('register', ['role' => 'student']) }}">
                                        <i class="bi bi-mortarboard-fill text-success me-2"></i> Create Account for Students
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 small fw-semibold" href="{{ route('register', ['role' => 'driver']) }}">
                                        <i class="bi bi-car-front-fill text-warning me-2"></i> Create Account for Drivers
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Notifications -->
    <div class="container pt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Modern Campus Footer -->
    <footer class="footer-campus">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <x-campusride-logo tagline light />
                    </div>
                    <p class="small text-muted mb-3">
                        The official university mobility platform. Providing safe, reliable, and convenient transport for students, faculty, and campus staff across all campus facilities.
                    </p>
                    <div class="d-flex gap-2">
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                            <i class="bi bi-shield-check me-1"></i> University Certified
                        </span>
                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                            <i class="bi bi-lightning-charge me-1"></i> Eco-Friendly Fleet
                        </span>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right me-1"></i> Home</a></li>
                        <li><a href="{{ route('about') }}"><i class="bi bi-chevron-right me-1"></i> About Services</a></li>
                        <li><a href="{{ route('services') }}"><i class="bi bi-chevron-right me-1"></i> Routes & Shuttles</a></li>
                        <li><a href="{{ route('driver.apply') }}"><i class="bi bi-chevron-right me-1"></i> Apply to Drive</a></li>
                        <li><a href="{{ route('contact') }}"><i class="bi bi-chevron-right me-1"></i> Contact Dispatch</a></li>
                        <li><a href="{{ route('login') }}"><i class="bi bi-chevron-right me-1"></i> Portal Login</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Mobility Hours</h6>
                    <ul class="list-unstyled small text-muted d-flex flex-column gap-2">
                        <li>{{ $publicOperatingHours }}</li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="text-white fw-bold mb-3">Emergency & Dispatch</h6>
                    <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                        <div class="d-flex align-items-center gap-2 text-info fw-bold mb-1">
                            <i class="bi bi-telephone-fill"></i>
                            <span>{{ $publicContactPhone }}</span>
                        </div>
                        <div class="small text-white-50 mb-2">Internal: Ext. 4400</div>
                        <div class="small text-light mb-1">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Campus Center, Building B, Room 102
                        </div>
                        <a class="small text-white-50" href="mailto:{{ $publicContactEmail }}">{{ $publicContactEmail }}</a>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-top border-secondary border-opacity-25 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 small text-muted">
                <div>&copy; {{ date('Y') }} CampusRide Mobility Services. All university rights reserved.</div>
                <div class="d-flex gap-4">
                    <a href="{{ route('services') }}">Privacy & Terms</a>
                    <a href="{{ route('contact') }}">Accessibility Support</a>
                    <a href="{{ route('login') }}">Driver & Admin Access</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
