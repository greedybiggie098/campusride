<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Campus Ride — CampusRide</title>
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
        .fare-box {
            background: linear-gradient(135deg, #0F5132 0%, #0B3D26 100%);
            color: #ffffff;
            border-radius: 16px;
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
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('passenger.dashboard') }}" class="text-decoration-none text-muted small fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                </a>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                    <i class="bi bi-check-circle me-1"></i> Campus Shuttles On Duty
                </span>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="rounded-3 bg-primary text-white p-3 fs-4">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">Request Campus Shuttle</h3>
                        <p class="text-muted small mb-0">Select your departure point and campus destination</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('passenger.rides.store') }}">
                    @csrf

                    <!-- Pickup Location -->
                    <div class="mb-4">
                        <label for="pickup_location" class="form-label small fw-bold text-muted text-uppercase">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Pickup Location <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-pin-map text-muted"></i></span>
                            <input type="text" name="pickup_location" id="pickup_location" list="campusStops" class="form-control border-start-0" required value="{{ old('pickup_location', 'North Residence Halls') }}" placeholder="Type or select a campus location...">
                        </div>
                        <div class="form-text small">Select from standard campus stops or enter a custom hall name.</div>
                    </div>

                    <!-- Dropoff Location -->
                    <div class="mb-4">
                        <label for="dropoff_location" class="form-label small fw-bold text-muted text-uppercase">
                            <i class="bi bi-flag-fill text-success me-1"></i> Destination Hub <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-compass text-muted"></i></span>
                            <input type="text" name="dropoff_location" id="dropoff_location" list="campusStops" class="form-control border-start-0" required value="{{ old('dropoff_location', 'Main Library Quad') }}" placeholder="Type or select destination...">
                        </div>
                    </div>

                    <!-- Datalist of Campus Locations -->
                    <datalist id="campusStops">
                        @foreach ($locations as $loc)
                            <option value="{{ $loc }}">
                        @endforeach
                    </datalist>

                    <!-- Payment Method -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-2">
                            <i class="bi bi-credit-card-2-front text-info me-1"></i> Payment Method <span class="text-danger">*</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="card border rounded-3 p-3 h-100 cursor-pointer d-flex align-items-center gap-2">
                                    <input class="form-check-input mt-0" type="radio" name="payment_method" value="campus_card" checked>
                                    <div>
                                        <div class="fw-bold small text-dark">Campus Card</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Student ID Balance</div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="card border rounded-3 p-3 h-100 cursor-pointer d-flex align-items-center gap-2">
                                    <input class="form-check-input mt-0" type="radio" name="payment_method" value="student_wallet" {{ old('payment_method') == 'student_wallet' ? 'checked' : '' }}>
                                    <div>
                                        <div class="fw-bold small text-dark">Student Wallet</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Digital Account</div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="card border rounded-3 p-3 h-100 cursor-pointer d-flex align-items-center gap-2">
                                    <input class="form-check-input mt-0" type="radio" name="payment_method" value="cash" {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                    <div>
                                        <div class="fw-bold small text-dark">Cash</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Pay to Driver</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Priority Toggle -->
                    <div class="p-3 bg-light rounded-3 mb-4 border">
                        <div class="form-check form-switch d-flex align-items-center justify-content-between ps-0">
                            <div>
                                <label class="form-check-label fw-bold text-dark mb-0" for="is_priority">
                                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Priority Express Shuttle
                                </label>
                                <div class="text-muted small">Higher dispatch priority for exam emergencies & late schedules (+$1.00).</div>
                            </div>
                            <input class="form-check-input ms-3" type="checkbox" role="switch" id="is_priority" name="is_priority" value="1" onchange="updateFare()">
                        </div>
                    </div>

                    <!-- Special Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label small fw-bold text-muted text-uppercase">
                            Notes / Special Requests (Optional)
                        </label>
                        <textarea name="notes" id="notes" rows="2" class="form-control" placeholder="e.g. Waiting under the canopy near Gate 2, travelling with a bike/luggage...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Fare Preview Box -->
                    <div class="fare-box p-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-info text-dark fw-bold mb-1">Standard Subsidized Rate</span>
                                <h5 class="fw-bold mb-0 text-white">Estimated Campus Fare</h5>
                                <div class="small text-light text-opacity-75">Flat fee across all campus stops</div>
                            </div>
                            <div class="text-end">
                                <span class="display-6 fw-bold text-warning" id="fareDisplay">$2.50</span>
                                <div class="small text-white-50">No hidden fees</div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-6 shadow-sm rounded-3">
                        <i class="bi bi-send-check-fill me-2"></i> Submit Ride Request
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
function updateFare() {
    const isPriority = document.getElementById('is_priority').checked;
    document.getElementById('fareDisplay').textContent = isPriority ? '$3.50' : '$2.50';
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
