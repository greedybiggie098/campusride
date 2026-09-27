<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard — CampusRide</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3.3 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --cr-primary: #064E3B;
            --cr-secondary: #16A34A;
            --cr-accent: #F4B400;
            --cr-light: #F5F8F6;
            --cr-dark: #17211D;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(180deg, #F5F8F6 0%, #eef5f1 100%);
            color: var(--cr-dark);
            min-height: 100vh;
        }
        .navbar-campus {
            background-color: rgba(255,255,255,0.96);
            box-shadow: 0 10px 30px rgba(6, 78, 59, 0.08);
        }
        .stat-card {
            border: 1px solid rgba(6, 78, 59, 0.08);
            border-radius: 18px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 10px 25px rgba(6, 78, 59, 0.05);
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 30px rgba(6, 78, 59, 0.1);
        }
        .active-trip-card {
            background: linear-gradient(135deg, var(--cr-primary) 0%, var(--cr-secondary) 100%);
            color: #ffffff;
            border-radius: 22px;
            box-shadow: 0 18px 40px rgba(6, 78, 59, 0.2);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-campus py-3 sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark" href="{{ route('home') }}">
            <span class="badge bg-primary rounded-3 p-2 text-white"><i class="bi bi-bus-front-fill"></i></span>
            <span>Campus<span class="text-info">Ride</span></span>
            <span class="badge bg-light text-muted border ms-2">Student Portal</span>
        </a>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('passenger.dashboard') }}" class="btn btn-sm btn-primary active fw-semibold">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
            <a href="{{ route('passenger.rides.create') }}" class="btn btn-sm btn-dark fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Request Ride
            </a>
            <a href="{{ route('passenger.rides.index') }}" class="btn btn-sm btn-outline-secondary fw-semibold">
                <i class="bi bi-clock-history me-1"></i> My Rides
            </a>

            <div class="dropdown ms-2">
                <button class="btn btn-sm btn-light border dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <x-user-avatar :user="auth()->user()" :size="24" />
                    <span>{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li class="px-3 py-2 small text-muted border-bottom">
                        <div><strong>{{ auth()->user()->name }}</strong></div>
                        <div>{{ auth()->user()->email }}</div>
                        <div class="badge bg-info-subtle text-info mt-1">{{ $passenger->student_id ?? 'Student' }}</div>
                    </li>
                    <li><a class="dropdown-item py-2" href="{{ route('passenger.rides.index') }}"><i class="bi bi-receipt me-2"></i>Ride History</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person-circle me-2"></i>Profile &amp; Settings</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('home') }}"><i class="bi bi-globe me-2"></i>CampusRide Home</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <div>{{ $errors->first() }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Student Welcome Banner -->
    <div class="card border-0 shadow-sm p-4 mb-4 bg-white rounded-4">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 text-muted small mb-1">
                    <span class="badge bg-primary-subtle text-primary fw-semibold">{{ $passenger->course ?? 'Undergraduate Student' }}</span>
                    <span>&bull;</span>
                    <span>{{ $passenger->department ?? 'General Campus' }}</span>
                    <span>&bull;</span>
                    <span>ID: <strong>{{ $passenger->student_id ?? 'STU-' . auth()->id() }}</strong></span>
                </div>
                <h2 class="h3 fw-bold mb-1 text-dark">Welcome, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-muted mb-0">Book subsidized campus shuttles across student halls, libraries, and science faculties.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('passenger.rides.create') }}" class="btn btn-primary btn-lg px-4 rounded-3 shadow-sm fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i> Request a Ride
                </a>
            </div>
        </div>
    </div>

    <!-- Active Trip Banner (if any) -->
    @if ($activeRide)
        <div class="active-trip-card p-4 mb-4">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="spinner-grow spinner-grow-sm text-warning" role="status"></span>
                        <span class="badge bg-warning text-dark fw-bold text-uppercase">Trip Active: {{ ucfirst(str_replace('_', ' ', $activeRide->status)) }}</span>
                        <span class="text-white-50 small">Requested {{ $activeRide->requested_at->diffForHumans() }}</span>
                    </div>
                    <h4 class="fw-bold mb-2">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $activeRide->pickup_location }}
                        <i class="bi bi-arrow-right text-info mx-2"></i>
                        <i class="bi bi-pin-map-fill text-success me-1"></i> {{ $activeRide->dropoff_location }}
                    </h4>
                    <div class="text-white-50 small">
                        @if ($activeRide->driver)
                            <span><i class="bi bi-person-badge-fill text-info me-1"></i> Driver: <strong class="text-white">{{ $activeRide->driver->user->name }}</strong></span>
                            <span class="mx-2">&bull;</span>
                            <span><i class="bi bi-car-front-fill text-info me-1"></i> {{ $activeRide->driver->vehicle_model }} ({{ $activeRide->driver->plate_number }})</span>
                        @else
                            <span><i class="bi bi-clock-history me-1"></i> Waiting for driver assignment from dispatch...</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('passenger.rides.show', $activeRide) }}" class="btn btn-info text-dark fw-bold px-4 py-2 rounded-3 shadow-sm">
                        View Live Ride Details <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Metrics Row -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Total Rides</span>
                    <span class="rounded-3 bg-primary-subtle text-primary p-2"><i class="bi bi-car-front"></i></span>
                </div>
                <div class="fs-3 fw-bold text-dark">{{ $stats['total_rides'] }}</div>
                <div class="text-muted small">Campus journeys</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Active Trips</span>
                    <span class="rounded-3 bg-warning-subtle text-warning p-2"><i class="bi bi-hourglass-split"></i></span>
                </div>
                <div class="fs-3 fw-bold text-dark">{{ $stats['active_rides'] }}</div>
                <div class="text-muted small">In progress/pending</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Completed</span>
                    <span class="rounded-3 bg-success-subtle text-success p-2"><i class="bi bi-check2-circle"></i></span>
                </div>
                <div class="fs-3 fw-bold text-success">{{ $stats['completed_rides'] }}</div>
                <div class="text-muted small">Safe arrivals</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Total Spent</span>
                    <span class="rounded-3 bg-info-subtle text-info p-2"><i class="bi bi-wallet2"></i></span>
                </div>
                <div class="fs-3 fw-bold text-dark">${{ number_format($stats['total_spent'], 2) }}</div>
                <div class="text-muted small">Subsidized student rate</div>
            </div>
        </div>
    </div>

    <!-- Recent Rides Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white py-3 px-4 border-0 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 text-dark">Recent Ride Requests</h5>
                <span class="text-muted small">Your latest campus travel history</span>
            </div>
            <a href="{{ route('passenger.rides.index') }}" class="btn btn-sm btn-outline-primary fw-semibold">
                View All Rides <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            @if ($recentRides->isEmpty())
                <div class="text-center py-5 text-muted">
                    <div class="display-6 mb-2">🚗</div>
                    <h6 class="fw-bold">No ride requests yet</h6>
                    <p class="small text-muted mb-3">Heading to a lecture or back to your residence?</p>
                    <a href="{{ route('passenger.rides.create') }}" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-plus-circle me-1"></i> Request First Ride
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4"># Ride</th>
                                <th>Pickup Hub</th>
                                <th>Destination</th>
                                <th>Driver & Vehicle</th>
                                <th>Fare</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th class="text-end pe-4">Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentRides as $ride)
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#{{ $ride->id }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $ride->pickup_location }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $ride->dropoff_location }}</div>
                                    </td>
                                    <td>
                                        @if ($ride->driver)
                                            <div class="fw-semibold text-dark">{{ $ride->driver->user->name }}</div>
                                            <div class="text-muted small">{{ $ride->driver->vehicle_model }}</div>
                                        @else
                                            <span class="text-muted small fst-italic">Waiting driver</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">${{ number_format($ride->fare, 2) }}</div>
                                        <span class="badge bg-{{ $ride->getPaymentBadgeClass() }}-subtle text-{{ $ride->getPaymentBadgeClass() }} small">
                                            {{ ucfirst($ride->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $ride->getStatusBadgeClass() }} px-2 py-1">
                                            {{ ucfirst(str_replace('_', ' ', $ride->status)) }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">{{ $ride->requested_at->format('M d, h:i A') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('passenger.rides.show', $ride) }}" class="btn btn-sm btn-outline-dark">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
