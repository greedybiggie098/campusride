<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard — CampusRide</title>
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
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-campus py-3 sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark" href="{{ route('home') }}">
            <span class="badge bg-dark rounded-3 p-2 text-white"><i class="bi bi-car-front-fill"></i></span>
            <span>Campus<span class="text-info">Ride</span></span>
            <span class="badge bg-info-subtle text-info border border-info-subtle ms-2">Driver Portal</span>
        </a>

        <div class="d-flex align-items-center gap-2">
            <!-- Availability Status Indicator -->
            <form method="POST" action="{{ route('driver.availability.toggle') }}" class="d-inline">
                @csrf
                @method('PATCH')
                @if ($driver->availability === 'available')
                    <button type="submit" class="btn btn-sm btn-success fw-bold d-flex align-items-center gap-1 shadow-sm" title="Click to go Off Duty">
                        <span class="spinner-grow spinner-grow-sm" role="status"></span>
                        <span>Online & Available</span>
                    </button>
                @elseif ($driver->availability === 'on_trip')
                    <span class="badge bg-primary fs-6 px-3 py-2 d-inline-flex align-items-center gap-1">
                        <i class="bi bi-geo-alt-fill"></i> On Trip
                    </span>
                @else
                    <button type="submit" class="btn btn-sm btn-outline-secondary fw-bold d-flex align-items-center gap-1" title="Click to go Online">
                        <i class="bi bi-moon-fill"></i> Off Duty (Go Online)
                    </button>
                @endif
            </form>

            <a href="{{ route('driver.rides.index') }}" class="btn btn-sm btn-outline-dark fw-semibold ms-2">
                <i class="bi bi-clock-history me-1"></i> Trip History
            </a>

            <div class="dropdown ms-2">
                <button class="btn btn-sm btn-light border dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                    <x-user-avatar :user="auth()->user()" :size="24" />
                    <span>{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li class="px-3 py-2 small text-muted border-bottom">
                        <div><strong>{{ auth()->user()->name }}</strong></div>
                        <div class="font-monospace small">{{ $driver->plate_number ?? 'No Plate' }}</div>
                    </li>
                    <li><a class="dropdown-item py-2" href="{{ route('driver.rides.index') }}"><i class="bi bi-receipt me-2"></i>My Trips</a></li>
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

    <!-- Driver Vehicle & Shift Banner -->
    <div class="card border-0 shadow-sm p-4 mb-4 bg-white rounded-4">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 text-muted small mb-1">
                    <span class="badge bg-dark text-white font-monospace">{{ $driver->plate_number ?? 'CR-SHUTTLE' }}</span>
                    <span>&bull;</span>
                    <span>{{ $driver->vehicle_model ?? 'Campus Transit Van' }} ({{ $driver->vehicle_type ?? 'Shuttle' }})</span>
                    <span>&bull;</span>
                    <span>Lic: <strong>{{ $driver->license_number ?? 'DRV-CAMPUS' }}</strong></span>
                </div>
                <h2 class="h3 fw-bold mb-1 text-dark">Driver Station: {{ auth()->user()->name }}</h2>
                <p class="text-muted mb-0">Manage assigned campus shuttle pickups, passenger routing, and payment verification.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <form method="POST" action="{{ route('driver.availability.toggle') }}">
                    @csrf
                    @method('PATCH')
                    @if ($driver->availability === 'available')
                        <button type="submit" class="btn btn-outline-secondary btn-lg px-4 rounded-3 fw-semibold">
                            <i class="bi bi-power me-1"></i> End Shift / Go Off Duty
                        </button>
                    @else
                        <button type="submit" class="btn btn-success btn-lg px-4 rounded-3 shadow-sm fw-semibold">
                            <i class="bi bi-broadcast me-1"></i> Start Shift / Go Online
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Metrics Row -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Active Queue</span>
                    <span class="rounded-3 bg-warning-subtle text-warning p-2"><i class="bi bi-hourglass-split"></i></span>
                </div>
                <div class="fs-3 fw-bold text-dark">{{ $stats['active_trips'] }}</div>
                <div class="text-muted small">Assigned / In Progress</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Trips Today</span>
                    <span class="rounded-3 bg-info-subtle text-info p-2"><i class="bi bi-calendar2-check"></i></span>
                </div>
                <div class="fs-3 fw-bold text-dark">{{ $stats['today_completed'] }}</div>
                <div class="text-muted small">Completed today</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Total Completed</span>
                    <span class="rounded-3 bg-success-subtle text-success p-2"><i class="bi bi-check2-all"></i></span>
                </div>
                <div class="fs-3 fw-bold text-success">{{ $stats['total_completed'] }}</div>
                <div class="text-muted small">Lifetime journeys</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-white p-3 h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Fares Reconciled</span>
                    <span class="rounded-3 bg-primary-subtle text-primary p-2"><i class="bi bi-cash-stack"></i></span>
                </div>
                <div class="fs-3 fw-bold text-primary">${{ number_format($stats['total_fare'], 2) }}</div>
                <div class="text-muted small">Campus transit balance</div>
            </div>
        </div>
    </div>

    <!-- Active Rides Queue -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-white py-3 px-4 border-0 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 text-dark">🚗 Active Trips & Assigned Queue</h5>
                <span class="text-muted small">Rides waiting for acceptance or currently on the road</span>
            </div>
            <span class="badge bg-dark">{{ $activeRides->count() }} active</span>
        </div>
        <div class="card-body p-0">
            @if ($activeRides->isEmpty())
                <div class="text-center py-5 text-muted">
                    <div class="display-6 mb-2">☕</div>
                    <h6 class="fw-bold">No trips assigned right now</h6>
                    <p class="small text-muted mb-0">Stay online. Campus dispatch will assign upcoming student ride requests to your shuttle.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Student Passenger</th>
                                <th>Pickup Point</th>
                                <th>Destination Point</th>
                                <th>Fare</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeRides as $ride)
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#{{ $ride->id }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $ride->passenger->user->name }}</div>
                                        @if ($ride->passenger->user->phone)
                                            <a href="tel:{{ $ride->passenger->user->phone }}" class="small text-primary text-decoration-none">
                                                <i class="bi bi-telephone-fill me-1"></i>{{ $ride->passenger->user->phone }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $ride->pickup_location }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark"><i class="bi bi-pin-map-fill text-success me-1"></i>{{ $ride->dropoff_location }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">${{ number_format($ride->fare, 2) }}</div>
                                        <span class="badge bg-light text-dark border small text-capitalize">{{ str_replace('_', ' ', $ride->payment_method) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $ride->getStatusBadgeClass() }} px-2 py-1">
                                            {{ ucfirst(str_replace('_', ' ', $ride->status)) }}
                                        </span>
                                        @if ($ride->is_priority)
                                            <span class="badge bg-danger ms-1">Priority</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ride->status === 'assigned')
                                            <form method="POST" action="{{ route('driver.rides.accept', $ride) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success fw-bold">
                                                    <i class="bi bi-check-lg me-1"></i> Accept Ride
                                                </button>
                                            </form>
                                        @elseif ($ride->status === 'accepted')
                                            <form method="POST" action="{{ route('driver.rides.status', $ride) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="in_progress">
                                                <button type="submit" class="btn btn-sm btn-primary fw-bold">
                                                    <i class="bi bi-play-fill me-1"></i> Start Trip
                                                </button>
                                            </form>
                                        @elseif ($ride->status === 'in_progress')
                                            <form method="POST" action="{{ route('driver.rides.status', $ride) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-sm btn-dark fw-bold">
                                                    <i class="bi bi-check2-all me-1"></i> Complete & Paid
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('driver.rides.show', $ride) }}" class="btn btn-sm btn-outline-secondary ms-1">
                                            Details
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
