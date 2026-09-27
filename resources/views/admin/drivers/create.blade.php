<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Driver — CampusRide Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8F9FA; color: #1e293b; min-height: 100vh; }
        .navbar-campus { background: #0f172a; }
        .form-card { border: 0; border-radius: 1rem; box-shadow: 0 8px 24px rgba(15, 81, 50, .08); }
        .section-title { color: #0f5132; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-campus py-2 sticky-top shadow-sm">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('admin.dashboard') }}">
            <span class="badge bg-warning text-dark rounded-3 p-2"><i class="bi bi-bus-front-fill"></i></span>
            <span>Campus<span class="text-warning">Ride</span></span>
            <span class="badge bg-danger ms-2">Admin Command</span>
        </a>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.drivers.index') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-arrow-left me-1"></i> Driver Fleet</a>
            <span class="text-white-50 small d-none d-md-inline">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-warning">Logout</button>
            </form>
        </div>
    </div>
</nav>

<main class="container-fluid px-lg-4 py-4">
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-1">Register New Driver</h1>
        <p class="text-muted small mb-0">Create a driver account and assign their campus vehicle.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm" role="alert">
            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please correct the following:</div>
            <ul class="mb-0 ps-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.drivers.store') }}">
        @csrf
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card form-card h-100 bg-white p-4">
                    <h2 class="h5 fw-bold section-title mb-4"><i class="bi bi-person-badge me-2"></i>Account Details</h2>
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Full name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold">Phone <span class="text-muted fw-normal">(optional)</span></label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="password" class="form-label fw-semibold">Temporary password</label>
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8" required>
                        <div class="form-text">Use at least 8 characters.</div>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card form-card h-100 bg-white p-4">
                    <h2 class="h5 fw-bold section-title mb-4"><i class="bi bi-car-front me-2"></i>Vehicle Details</h2>
                    <div class="mb-3">
                        <label for="license_number" class="form-label fw-semibold">Driver license number</label>
                        <input id="license_number" type="text" name="license_number" value="{{ old('license_number') }}" class="form-control @error('license_number') is-invalid @enderror" required>
                        @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="vehicle_type" class="form-label fw-semibold">Vehicle type</label>
                            <input id="vehicle_type" type="text" name="vehicle_type" value="{{ old('vehicle_type') }}" class="form-control @error('vehicle_type') is-invalid @enderror" placeholder="e.g. Shuttle bus" required>
                            @error('vehicle_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="vehicle_model" class="form-label fw-semibold">Vehicle model</label>
                            <input id="vehicle_model" type="text" name="vehicle_model" value="{{ old('vehicle_model') }}" class="form-control @error('vehicle_model') is-invalid @enderror" placeholder="e.g. Toyota Hiace" required>
                            @error('vehicle_model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="plate_number" class="form-label fw-semibold">Plate number</label>
                        <input id="plate_number" type="text" name="plate_number" value="{{ old('plate_number') }}" class="form-control @error('plate_number') is-invalid @enderror" required>
                        @error('plate_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="availability" class="form-label fw-semibold">Availability</label>
                            <select id="availability" name="availability" class="form-select @error('availability') is-invalid @enderror" required>
                                <option value="available" @selected(old('availability', 'available') === 'available')>Available</option>
                                <option value="off_duty" @selected(old('availability') === 'off_duty')>Off Duty</option>
                            </select>
                            @error('availability') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">Account status</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-success fw-semibold"><i class="bi bi-person-plus-fill me-1"></i> Register Driver</button>
        </div>
    </form>
</main>
</body>
</html>
