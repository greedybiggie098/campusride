<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride Receipt #{{ $ride->id }} — CampusRide</title>
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
        .timeline-step {
            position: relative;
            padding-bottom: 1.5rem;
            border-left: 2px solid #e2e8f0;
            padding-left: 1.5rem;
        }
        .timeline-step:last-child {
            border-left: 2px solid transparent;
            padding-bottom: 0;
        }
        .timeline-dot {
            position: absolute;
            left: -9px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: #cbd5e1;
        }
        .timeline-dot.active {
            background-color: #0F5132;
            box-shadow: 0 0 0 4px rgba(15, 52, 96, 0.2);
        }
        .timeline-dot.completed {
            background-color: #198754;
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
            <a href="{{ route('passenger.rides.index') }}" class="btn btn-sm btn-outline-secondary fw-semibold">
                <i class="bi bi-clock-history me-1"></i> My Rides
            </a>
        </div>
    </div>
</nav>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('passenger.rides.index') }}" class="text-decoration-none text-muted small fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Back to My Rides
            </a>
            <h2 class="h3 fw-bold mb-0 text-dark mt-1">Campus Ride #{{ $ride->id }}</h2>
        </div>
        <div>
            <span class="badge bg-{{ $ride->getStatusBadgeClass() }} fs-6 px-3 py-2">
                Status: {{ ucfirst(str_replace('_', ' ', $ride->status)) }}
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Journey Details & Digital Receipt -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5 mb-4">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                    <div>
                        <span class="badge bg-primary-subtle text-primary fw-bold mb-2">Official University Transit Receipt</span>
                        <h4 class="fw-bold mb-1 text-dark">Trip Route & Details</h4>
                        <div class="text-muted small">Booking Timestamp: {{ $ride->requested_at->format('F d, Y \a\t h:i A') }}</div>
                    </div>
                    <div class="text-end">
                        <div class="display-6 fw-bold text-primary">${{ number_format($ride->fare, 2) }}</div>
                        <span class="badge bg-{{ $ride->getPaymentBadgeClass() }}-subtle text-{{ $ride->getPaymentBadgeClass() }} fw-bold">
                            Payment: {{ ucfirst($ride->payment_status) }}
                        </span>
                    </div>
                </div>

                <!-- Locations Card -->
                <div class="p-3 bg-light rounded-4 border mb-4">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Pickup Hub</span>
                            <h5 class="fw-bold text-dark mb-0">{{ $ride->pickup_location }}</h5>
                            @if ($ride->pickup_at)
                                <div class="text-muted small">Picked up at {{ $ride->pickup_at->format('h:i A') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                            <i class="bi bi-pin-map-fill"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Destination Hub</span>
                            <h5 class="fw-bold text-dark mb-0">{{ $ride->dropoff_location }}</h5>
                            @if ($ride->completed_at)
                                <div class="text-success small fw-semibold">Arrived safely at {{ $ride->completed_at->format('h:i A') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment & Billing Information -->
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-receipt text-info me-2"></i>Payment Breakdown</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted">Payment Method</td>
                                <td class="fw-semibold text-end text-capitalize">{{ str_replace('_', ' ', $ride->payment_method) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Standard Campus Base Rate</td>
                                <td class="fw-semibold text-end">$2.50</td>
                            </tr>
                            @if ($ride->is_priority)
                                <tr>
                                    <td class="text-muted">Priority Express Dispatch</td>
                                    <td class="fw-semibold text-end">$1.00</td>
                                </tr>
                            @endif
                            <tr class="table-light">
                                <td class="fw-bold text-dark">Total Campus Fare</td>
                                <td class="fw-bold text-dark text-end fs-5">${{ number_format($ride->fare, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if ($ride->notes)
                    <div class="p-3 bg-light rounded-3 mb-4">
                        <span class="small fw-bold text-muted text-uppercase d-block mb-1">Special Notes / Requests:</span>
                        <p class="small text-dark mb-0">{{ $ride->notes }}</p>
                    </div>
                @endif

                @if ($ride->status === 'pending')
                    <div class="d-flex justify-content-end">
                        <form method="POST" action="{{ route('passenger.rides.cancel', $ride) }}" onsubmit="return confirm('Are you sure you want to cancel this ride request?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger btn-sm px-3">
                                <i class="bi bi-x-circle me-1"></i> Cancel Ride Request
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar: Driver Card & Live Status Tracker -->
        <div class="col-lg-4">
            <!-- Assigned Driver Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge-fill text-primary me-2"></i>Assigned Shuttle</h5>
                @if ($ride->driver)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.25rem;">
                            {{ strtoupper(substr($ride->driver->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">{{ $ride->driver->user->name }}</h6>
                            <span class="badge bg-success-subtle text-success small">Verified Campus Driver</span>
                        </div>
                    </div>
                    <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-0">
                        <li><strong>Vehicle:</strong> {{ $ride->driver->vehicle_model }} ({{ $ride->driver->vehicle_type }})</li>
                        <li><strong>Plate Number:</strong> <span class="badge bg-dark text-white font-monospace">{{ $ride->driver->plate_number }}</span></li>
                        @if ($ride->driver->user->phone)
                            <li><strong>Contact:</strong> <a href="tel:{{ $ride->driver->user->phone }}" class="text-decoration-none">{{ $ride->driver->user->phone }}</a></li>
                        @endif
                    </ul>
                @else
                    <div class="text-center py-4 text-muted">
                        <div class="spinner-border text-primary spinner-border-sm mb-2" role="status"></div>
                        <p class="small mb-0">Not assigned. Campus dispatch is matching your request with an available driver...</p>
                    </div>
                @endif
            </div>

            <!-- Journey Progress Timeline -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-geo-fill text-info me-2"></i>Live Status Tracker</h5>

                @php
                    $steps = [
                        'pending'     => ['title' => 'Ride Requested', 'desc' => 'Waiting for dispatch matching'],
                        'assigned'    => ['title' => 'Driver Assigned', 'desc' => 'Driver allocated to pickup route'],
                        'accepted'    => ['title' => 'Driver Accepted', 'desc' => 'Shuttle en route to pickup spot'],
                        'in_progress' => ['title' => 'Trip In Progress', 'desc' => 'On the way to destination'],
                        'completed'   => ['title' => 'Completed', 'desc' => 'Arrived safely at destination'],
                    ];
                    $statusOrder = ['pending', 'assigned', 'accepted', 'in_progress', 'completed'];
                    $currentIndex = array_search($ride->status, $statusOrder);
                @endphp

                @if ($ride->status === 'cancelled')
                    <div class="alert alert-danger mb-0 small">
                        <i class="bi bi-x-circle-fill me-1"></i> This ride request was cancelled.
                    </div>
                @else
                    <div>
                        @foreach ($steps as $key => $info)
                            @php
                                $stepIndex = array_search($key, $statusOrder);
                                $isDone = $currentIndex !== false && $stepIndex <= $currentIndex;
                                $isCurrent = $key === $ride->status;
                            @endphp
                            <div class="timeline-step">
                                <div class="timeline-dot {{ $isDone ? 'completed' : '' }} {{ $isCurrent ? 'active' : '' }}"></div>
                                <h6 class="fw-bold mb-0 {{ $isDone ? 'text-dark' : 'text-muted' }}">{{ $info['title'] }}</h6>
                                <p class="small text-muted mb-0">{{ $info['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
