<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusRide Operations — Admin Dashboard</title>
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
            background: linear-gradient(180deg, #F5F8F6 0%, #edf5f0 100%);
            color: var(--cr-dark);
            min-height: 100vh;
        }
        .navbar-campus {
            background: linear-gradient(135deg, var(--cr-primary) 0%, #0d503f 100%);
            box-shadow: 0 18px 34px rgba(6, 78, 59, 0.18);
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

<!-- Admin Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-campus py-2 sticky-top shadow-sm">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-white" href="{{ route('admin.dashboard') }}">
            <span class="badge bg-primary rounded-3 p-2 text-white"><i class="bi bi-shield-shaded"></i></span>
            <span>Campus<span class="text-info">Ride</span></span>
            <span class="badge bg-danger ms-2">Admin Command</span>
        </a>

        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link active fw-bold text-info" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="{{ route('admin.rides.index') }}">
                        <i class="bi bi-car-front me-1"></i> Ride Dispatch
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people me-1"></i> Students & Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="{{ route('admin.drivers.index') }}">
                        <i class="bi bi-ev-front me-1"></i> Drivers Fleet
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="{{ route('admin.driver-applications.index') }}">
                        <i class="bi bi-person-badge me-1"></i> Driver Apps
                        @if ($stats['pending_driver_apps'] > 0)
                            <span class="badge bg-warning text-dark rounded-pill ms-1">{{ $stats['pending_driver_apps'] }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="{{ route('admin.messages.index') }}">
                        <i class="bi bi-chat-left-text me-1"></i> Inquiries
                        @if ($stats['inquiries'] > 0)
                            <span class="badge bg-danger rounded-pill ms-1">{{ $stats['inquiries'] }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="{{ route('admin.settings.edit') }}">
                        <i class="bi bi-sliders me-1"></i> Settings
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light" target="_blank" title="Preview Public Site">
                    <i class="bi bi-globe me-1"></i> Live Site
                </a>
                <div class="dropdown">
                    <button class="btn btn-sm btn-dark border border-secondary dropdown-toggle d-flex align-items-center gap-2 text-white" type="button" data-bs-toggle="dropdown">
                        <x-user-avatar :user="auth()->user()" :size="24" />
                        <span>{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li class="px-3 py-2 small text-muted border-bottom">
                            <div><strong>{{ auth()->user()->name }}</strong></div>
                            <div>{{ auth()->user()->email }}</div>
                            <span class="badge bg-danger-subtle text-danger mt-1">Super Administrator</span>
                        </li>
                        <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person-circle me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('admin.rides.index') }}"><i class="bi bi-receipt me-2"></i>All Rides</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i>Students List</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('admin.drivers.index') }}"><i class="bi bi-car-front me-2"></i>Driver Fleet</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('admin.settings.edit') }}"><i class="bi bi-sliders me-2"></i>Application Settings</a></li>
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
    </div>
</nav>

<main class="container-fluid px-lg-4 py-4">
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

    <!-- Page Header & Quick Shortcuts -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1 text-dark">University Mobility Command Center</h1>
            <p class="text-muted small mb-0">Overview of fleet availability, real-time trip requests, passenger activity, and revenue.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.rides.index') }}" class="btn btn-sm btn-dark">
                <i class="bi bi-card-list me-1"></i> All Rides
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-person-plus me-1"></i> Add Student
            </a>
            <a href="{{ route('admin.drivers.create') }}" class="btn btn-sm btn-success">
                <i class="bi bi-plus-circle me-1"></i> Add Driver
            </a>
            <a href="{{ route('admin.driver-applications.index') }}" class="btn btn-sm btn-warning text-dark fw-bold">
                <i class="bi bi-person-badge me-1"></i> Driver Applications
                @if ($stats['pending_driver_apps'] > 0)
                    <span class="badge bg-dark text-white ms-1">{{ $stats['pending_driver_apps'] }}</span>
                @endif
            </a>
        </div>
    </div>

    @if ($stats['pending_driver_apps'] > 0)
        <div class="alert alert-warning border-0 shadow-sm d-flex flex-column flex-md-row align-items-md-center justify-content-between p-3 mb-4 rounded-3 gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 bg-warning text-dark p-2 fs-4">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">{{ $stats['pending_driver_apps'] }} new driver application(s) awaiting review</h6>
                    <p class="small text-muted mb-0">Review credentials, vehicle specifications, and approve driver accounts.</p>
                </div>
            </div>
            <a href="{{ route('admin.driver-applications.index') }}" class="btn btn-sm btn-dark fw-semibold text-nowrap">
                Review Applications &rarr;
            </a>
        </div>
    @endif

    <!-- Platform KPI Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card bg-white p-3 h-100 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold">Pending</span>
                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i></span>
                </div>
                <div class="fs-3 fw-bold text-dark">{{ $stats['pending'] }}</div>
                <div class="text-muted small">Need Assignment</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card bg-white p-3 h-100 border-start border-4 border-info">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold">Assigned</span>
                    <span class="badge bg-info text-dark"><i class="bi bi-arrow-repeat"></i></span>
                </div>
                <div class="fs-3 fw-bold text-dark">{{ $stats['assigned'] }}</div>
                <div class="text-muted small">Assigned/Accepted</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card bg-white p-3 h-100 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold">On Trip</span>
                    <span class="badge bg-primary text-white"><i class="bi bi-geo-alt-fill"></i></span>
                </div>
                <div class="fs-3 fw-bold text-primary">{{ $stats['active'] }}</div>
                <div class="text-muted small">In Progress</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card bg-white p-3 h-100 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold">Completed</span>
                    <span class="badge bg-success text-white"><i class="bi bi-check2-all"></i></span>
                </div>
                <div class="fs-3 fw-bold text-success">{{ $stats['completed'] }}</div>
                <div class="text-muted small">Safe Drop-offs</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card bg-white p-3 h-100 border-start border-4 border-dark">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold">Active Fleet</span>
                    <span class="badge bg-dark text-white"><i class="bi bi-bus-front"></i></span>
                </div>
                <div class="fs-3 fw-bold text-dark">{{ $stats['avail_drivers'] }} / {{ $stats['drivers'] }}</div>
                <div class="text-muted small">Available / Total</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card stat-card bg-white p-3 h-100 border-start border-4 border-secondary">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-muted small fw-semibold">Total Revenue</span>
                    <span class="badge bg-secondary text-white"><i class="bi bi-cash"></i></span>
                </div>
                <div class="fs-3 fw-bold text-dark">${{ number_format($stats['total_revenue'], 2) }}</div>
                <div class="text-muted small">{{ $stats['total_rides'] }} Total Rides</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Urgent Pending Requests Assignment Panel -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-white py-3 px-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-warning text-dark fw-bold mb-1">Live Queue</span>
                        <h5 class="fw-bold mb-0 text-dark">Pending Ride Requests ({{ $pendingRides->count() }})</h5>
                    </div>
                    <a href="{{ route('admin.rides.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-dark">
                        View Queue
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($pendingRides->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <div class="fs-1 mb-2">🎉</div>
                            <h6 class="fw-bold">No pending ride requests!</h6>
                            <p class="small text-muted mb-0">All student ride requests have been assigned to campus drivers.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Student</th>
                                        <th>Route</th>
                                        <th>Fare</th>
                                        <th>Requested</th>
                                        <th class="text-end pe-4">Assign Available Driver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingRides as $ride)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">{{ $ride->passenger->user->name }}</div>
                                                <div class="text-muted small">{{ $ride->passenger->student_id ?? 'Student' }}</div>
                                                @if ($ride->is_priority)
                                                    <span class="badge bg-danger small">Priority</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="small fw-semibold text-dark"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $ride->pickup_location }}</div>
                                                <div class="small fw-semibold text-muted"><i class="bi bi-pin-map-fill text-success me-1"></i>{{ $ride->dropoff_location }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-bold">${{ number_format($ride->fare, 2) }}</div>
                                                <span class="badge bg-light text-muted border small">{{ ucfirst($ride->payment_method) }}</span>
                                            </td>
                                            <td class="small text-muted">{{ $ride->requested_at->diffForHumans() }}</td>
                                            <td class="text-end pe-4">
                                                @if ($availableDrivers->isEmpty())
                                                    <span class="badge bg-danger-subtle text-danger p-2">No Drivers Available</span>
                                                @else
                                                    <form method="POST" action="{{ route('admin.rides.assign', $ride) }}" class="d-inline-flex gap-1 justify-content-end">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="driver_id" class="form-select form-select-sm" style="max-width: 170px;" required>
                                                            <option value="">Choose Driver...</option>
                                                            @foreach ($availableDrivers as $drv)
                                                                <option value="{{ $drv->id }}">
                                                                    {{ $drv->user->name }} ({{ $drv->plate_number }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            Assign
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Campus Driver Fleet Status -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-white py-3 px-4 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Fleet Monitor</h5>
                        <span class="text-muted small">{{ $allDrivers->count() }} Registered Shuttles</span>
                    </div>
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-sm btn-outline-dark">
                        Manage Fleet
                    </a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach ($allDrivers as $d)
                            <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold text-dark">{{ $d->user->name }}</div>
                                    <div class="small text-muted">{{ $d->vehicle_model }} &bull; <span class="badge bg-light text-dark border font-monospace">{{ $d->plate_number }}</span></div>
                                </div>
                                <div class="text-end">
                                    @if ($d->availability === 'available')
                                        <span class="badge bg-success">Available</span>
                                    @elseif ($d->availability === 'on_trip')
                                        <span class="badge bg-primary">On Trip</span>
                                    @else
                                        <span class="badge bg-secondary">Off Duty</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent System Rides Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white py-3 px-4 border-0 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-0 text-dark">Recent Platform Rides</h5>
                <span class="text-muted small">Latest campus activity across all routes</span>
            </div>
            <a href="{{ route('admin.rides.index') }}" class="btn btn-sm btn-outline-primary fw-semibold">
                View All {{ $stats['total_rides'] }} Rides <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Student Passenger</th>
                            <th>Pickup Hub</th>
                            <th>Destination</th>
                            <th>Assigned Driver</th>
                            <th>Fare</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th class="text-end pe-4">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentRides as $ride)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#{{ $ride->id }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $ride->passenger->user->name }}</div>
                                </td>
                                <td>{{ $ride->pickup_location }}</td>
                                <td>{{ $ride->dropoff_location }}</td>
                                <td>
                                    @if ($ride->driver)
                                        <span class="fw-semibold text-dark">{{ $ride->driver->user->name }}</span>
                                    @else
                                        <span class="text-muted small fst-italic">Unassigned</span>
                                    @endif
                                </td>
                                <td class="fw-bold">${{ number_format($ride->fare, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $ride->getPaymentBadgeClass() }}-subtle text-{{ $ride->getPaymentBadgeClass() }}">
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
                                    <a href="{{ route('admin.rides.show', $ride) }}" class="btn btn-sm btn-outline-dark">
                                        View & Dispatch
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
