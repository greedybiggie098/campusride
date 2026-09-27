<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Rides — CampusRide</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3.3 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F9FA;
            color: #1e293b;
            min-height: 100vh;
        }
        .navbar-campus {
            background-color: #ffffff;
            box-shadow: 0 4px 20px -2px rgba(15, 52, 96, 0.08);
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
            <a href="{{ route('passenger.dashboard') }}" class="btn btn-sm btn-outline-secondary fw-semibold">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
            <a href="{{ route('passenger.rides.create') }}" class="btn btn-sm btn-primary fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Request Ride
            </a>
            <a href="{{ route('passenger.rides.index') }}" class="btn btn-sm btn-dark active fw-semibold">
                <i class="bi bi-clock-history me-1"></i> My Rides
            </a>
        </div>
    </div>
</nav>

<main class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h3 fw-bold mb-1 text-dark">My Campus Rides</h2>
            <p class="text-muted small mb-0">Track all your scheduled, active, and past journeys</p>
        </div>
        <a href="{{ route('passenger.rides.create') }}" class="btn btn-primary fw-semibold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Request New Ride
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Pills -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="small fw-bold text-muted me-2"><i class="bi bi-funnel me-1"></i>Filter Status:</span>
            <a href="{{ route('passenger.rides.index') }}" class="btn btn-sm {{ empty($statusFilter) ? 'btn-dark' : 'btn-light border' }}">
                All ({{ auth()->user()->passenger->rides()->count() }})
            </a>
            <a href="{{ route('passenger.rides.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $statusFilter === 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-light border' }}">
                Pending
            </a>
            <a href="{{ route('passenger.rides.index', ['status' => 'in_progress']) }}" class="btn btn-sm {{ $statusFilter === 'in_progress' ? 'btn-primary' : 'btn-light border' }}">
                In Progress
            </a>
            <a href="{{ route('passenger.rides.index', ['status' => 'completed']) }}" class="btn btn-sm {{ $statusFilter === 'completed' ? 'btn-success' : 'btn-light border' }}">
                Completed
            </a>
            <a href="{{ route('passenger.rides.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ $statusFilter === 'cancelled' ? 'btn-danger' : 'btn-light border' }}">
                Cancelled
            </a>
        </div>
    </div>

    <!-- Rides Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-body p-0">
            @if ($rides->isEmpty())
                <div class="text-center py-5 text-muted">
                    <div class="display-6 mb-2">📋</div>
                    <h5 class="fw-bold">No rides matching this filter</h5>
                    <p class="small text-muted mb-3">You don't have any ride records under this criteria.</p>
                    <a href="{{ route('passenger.rides.create') }}" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-plus-circle me-1"></i> Book a Ride Now
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4"># Ride</th>
                                <th>Pickup Hub</th>
                                <th>Destination Hub</th>
                                <th>Driver & Shuttle</th>
                                <th>Fare</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rides as $ride)
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
                                            <div class="text-muted small">{{ $ride->driver->vehicle_model }} ({{ $ride->driver->plate_number }})</div>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Awaiting Driver</span>
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
                                        @if ($ride->is_priority)
                                            <span class="badge bg-danger ms-1" title="Priority Express">Priority</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ $ride->requested_at->format('M d, Y h:i A') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('passenger.rides.show', $ride) }}" class="btn btn-sm btn-outline-dark">
                                            View Receipt
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($rides->hasPages())
                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $rides->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
