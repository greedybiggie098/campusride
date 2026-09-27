<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Execution #{{ $ride->id }} — CampusRide Driver</title>
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
        .trip-action-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            border-radius: 18px;
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
            <a href="{{ route('driver.rides.index') }}" class="btn btn-sm btn-outline-secondary fw-semibold">
                <i class="bi bi-clock-history me-1"></i> Trip History
            </a>
        </div>
    </div>
</nav>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('driver.dashboard') }}" class="text-decoration-none text-muted small fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Back to Driver Dashboard
            </a>
            <h2 class="h3 fw-bold mb-0 text-dark mt-1">Trip Execution #{{ $ride->id }}</h2>
        </div>
        <div>
            <span class="badge bg-{{ $ride->getStatusBadgeClass() }} fs-6 px-3 py-2">
                Trip Status: {{ ucfirst(str_replace('_', ' ', $ride->status)) }}
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
            <div>{{ $errors->first() }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Trip Details & Actions -->
        <div class="col-lg-8">
            <!-- Driver Primary Action Box -->
            <div class="trip-action-card p-4 p-md-5 mb-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-warning text-dark fw-bold">Live Trip Controls</span>
                    <span class="text-white-50 small">Requested: {{ $ride->requested_at->format('h:i A') }}</span>
                </div>

                @if ($ride->status === 'assigned')
                    <h4 class="fw-bold mb-2">Trip is Assigned to You</h4>
                    <p class="text-light text-opacity-75 mb-4">Review the student pickup location below and accept to confirm your dispatch.</p>
                    <form method="POST" action="{{ route('driver.rides.accept', $ride) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-lg px-4 fw-bold shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i> Accept This Ride
                        </button>
                    </form>
                @elseif ($ride->status === 'accepted')
                    <h4 class="fw-bold mb-2">Drive to Pickup Location</h4>
                    <p class="text-light text-opacity-75 mb-4">Head towards <strong class="text-white">{{ $ride->pickup_location }}</strong>. When the student boards your shuttle, click Start Trip.</p>
                    <form method="POST" action="{{ route('driver.rides.status', $ride) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="btn btn-primary btn-lg px-4 fw-bold shadow-sm">
                            <i class="bi bi-play-circle-fill me-2"></i> Passenger Boarded / Start Trip
                        </button>
                    </form>
                @elseif ($ride->status === 'in_progress')
                    <h4 class="fw-bold mb-2">Trip In Progress</h4>
                    <p class="text-light text-opacity-75 mb-4">Drive safely to <strong class="text-white">{{ $ride->dropoff_location }}</strong>. Collect or verify the student fare before completing.</p>
                    <form method="POST" action="{{ route('driver.rides.status', $ride) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-success btn-lg px-4 fw-bold shadow-sm">
                            <i class="bi bi-check2-all me-2"></i> Complete Ride & Confirm Payment
                        </button>
                    </form>
                @elseif ($ride->status === 'completed')
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success text-white p-3 fs-3">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">Trip Successfully Completed</h4>
                            <p class="text-light text-opacity-75 mb-0">Completed at {{ $ride->completed_at ? $ride->completed_at->format('h:i A') : 'Earlier' }}. Payment verified.</p>
                        </div>
                    </div>
                @else
                    <div class="text-white-50">This trip has ended with status: {{ ucfirst($ride->status) }}.</div>
                @endif
            </div>

            <!-- Route Map / Points Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-signpost-2-fill text-primary me-2"></i>Campus Route & Stops</h5>

                <div class="p-4 bg-light rounded-4 border mb-4">
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <span class="badge bg-danger-subtle text-danger fw-bold mb-1">PICKUP LOCATION</span>
                            <h4 class="fw-bold text-dark mb-1">{{ $ride->pickup_location }}</h4>
                            <div class="text-muted small">
                                @if ($ride->pickup_at)
                                    Picked up: {{ $ride->pickup_at->format('h:i A') }}
                                @else
                                    Awaiting student boarding
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                            <i class="bi bi-pin-map-fill"></i>
                        </div>
                        <div>
                            <span class="badge bg-success-subtle text-success fw-bold mb-1">DESTINATION HUB</span>
                            <h4 class="fw-bold text-dark mb-1">{{ $ride->dropoff_location }}</h4>
                            <div class="text-muted small">
                                @if ($ride->completed_at)
                                    Arrived: {{ $ride->completed_at->format('h:i A') }}
                                @else
                                    Estimated transit: ~5 mins
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if ($ride->notes)
                    <div class="p-3 bg-warning-subtle text-dark rounded-3 border border-warning-subtle">
                        <div class="fw-bold small mb-1"><i class="bi bi-chat-left-text-fill me-1"></i>Passenger Instructions:</div>
                        <p class="mb-0 small">{{ $ride->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar: Passenger Card & Fare Collection -->
        <div class="col-lg-4">
            <!-- Student Passenger Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-fill text-primary me-2"></i>Student Details</h5>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.25rem;">
                        {{ strtoupper(substr($ride->passenger->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ $ride->passenger->user->name }}</h6>
                        <span class="badge bg-info-subtle text-info small">{{ $ride->passenger->student_id ?? 'Student' }}</span>
                    </div>
                </div>
                <ul class="list-unstyled small text-muted d-flex flex-column gap-2 mb-0">
                    <li><strong>Department:</strong> {{ $ride->passenger->department ?? 'Campus Faculty' }}</li>
                    <li><strong>Course:</strong> {{ $ride->passenger->course ?? 'Undergraduate' }}</li>
                    @if ($ride->passenger->user->phone)
                        <li>
                            <strong>Phone:</strong>
                            <a href="tel:{{ $ride->passenger->user->phone }}" class="text-decoration-none fw-bold text-primary">
                                <i class="bi bi-telephone-fill me-1"></i>{{ $ride->passenger->user->phone }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Fare Collection Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cash-coin text-success me-2"></i>Fare Information</h5>
                <div class="p-3 bg-light rounded-3 mb-3 text-center">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Due</span>
                    <div class="display-6 fw-bold text-primary mb-1">${{ number_format($ride->fare, 2) }}</div>
                    <span class="badge bg-{{ $ride->getPaymentBadgeClass() }} px-3 py-1">
                        {{ ucfirst($ride->payment_status) }}
                    </span>
                </div>
                <div class="small text-muted">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Payment Method:</span>
                        <strong class="text-dark text-capitalize">{{ str_replace('_', ' ', $ride->payment_method) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Priority Level:</span>
                        <strong class="text-dark">{{ $ride->is_priority ? 'Express Priority' : 'Standard' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
