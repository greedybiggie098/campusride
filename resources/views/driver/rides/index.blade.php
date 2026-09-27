<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Trip History — CampusRide</title>
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
            <span class="badge bg-dark rounded-3 p-2 text-white"><i class="bi bi-car-front-fill"></i></span>
            <span>Campus<span class="text-info">Ride</span></span>
            <span class="badge bg-info-subtle text-info border border-info-subtle ms-2">Driver Portal</span>
        </a>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('driver.dashboard') }}" class="btn btn-sm btn-outline-secondary fw-semibold">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
            <a href="{{ route('driver.rides.index') }}" class="btn btn-sm btn-dark active fw-semibold">
                <i class="bi bi-clock-history me-1"></i> Trip History
            </a>
        </div>
    </div>
</nav>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 fw-bold mb-1 text-dark">Driver Trip Log & History</h2>
            <p class="text-muted small mb-0">Record of all campus shuttle journeys assigned to your vehicle</p>
        </div>
        <a href="{{ route('driver.dashboard') }}" class="btn btn-outline-dark btn-sm fw-semibold">
            <i class="bi bi-speedometer2 me-1"></i> Driver Dashboard
        </a>
    </div>

    <!-- Filter Pills -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="small fw-bold text-muted me-2"><i class="bi bi-funnel me-1"></i>Filter Trips:</span>
            <a href="{{ route('driver.rides.index') }}" class="btn btn-sm {{ empty($statusFilter) ? 'btn-dark' : 'btn-light border' }}">
                All Trips
            </a>
            <a href="{{ route('driver.rides.index', ['status' => 'assigned']) }}" class="btn btn-sm {{ $statusFilter === 'assigned' ? 'btn-info text-white fw-bold' : 'btn-light border' }}">
                Assigned
            </a>
            <a href="{{ route('driver.rides.index', ['status' => 'in_progress']) }}" class="btn btn-sm {{ $statusFilter === 'in_progress' ? 'btn-primary' : 'btn-light border' }}">
                In Progress
            </a>
            <a href="{{ route('driver.rides.index', ['status' => 'completed']) }}" class="btn btn-sm {{ $statusFilter === 'completed' ? 'btn-success' : 'btn-light border' }}">
                Completed
            </a>
        </div>
    </div>

    <!-- Trips Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-body p-0">
            @if ($rides->isEmpty())
                <div class="text-center py-5 text-muted">
                    <div class="display-6 mb-2">📋</div>
                    <h5 class="fw-bold">No trips found</h5>
                    <p class="small text-muted mb-0">No rides matching this filter criteria.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Passenger</th>
                                <th>Pickup Hub</th>
                                <th>Destination</th>
                                <th>Fare</th>
                                <th>Status</th>
                                <th>Time</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rides as $ride)
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#{{ $ride->id }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $ride->passenger->user->name }}</div>
                                        <div class="text-muted small">{{ $ride->passenger->student_id ?? 'Student' }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $ride->pickup_location }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $ride->dropoff_location }}</div>
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
                                        <a href="{{ route('driver.rides.show', $ride) }}" class="btn btn-sm btn-outline-dark">
                                            Manage Trip
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
