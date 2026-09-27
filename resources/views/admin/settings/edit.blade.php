<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings — CampusRide Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8F9FA; color: #212529; min-height: 100vh; }
        .navbar-campus { background: #0F5132; }
        .settings-card { border: 0; border-radius: 1rem; box-shadow: 0 8px 24px rgba(15, 81, 50, .08); }
        .settings-card h2 { color: #0F5132; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-campus py-2 sticky-top shadow-sm">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('admin.dashboard') }}">
            <span class="badge bg-warning text-dark rounded-3 p-2"><i class="bi bi-sliders"></i></span>
            <span>Campus<span class="text-warning">Ride</span></span>
            <span class="badge bg-danger ms-2">Admin Command</span>
        </a>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-warning">Logout</button>
            </form>
        </div>
    </div>
</nav>

<main class="container py-4 py-lg-5">
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-1">Application Settings</h1>
        <p class="text-muted mb-0">Control the CampusRide identity, pricing, contact details, and operating hours.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm" role="alert"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="row g-4">
        @csrf
        @method('PATCH')

        <div class="col-lg-6">
            <section class="card settings-card h-100 p-4 p-lg-5 bg-white">
                <h2 class="h5 fw-bold mb-1"><i class="bi bi-building me-2"></i>Brand & Contact</h2>
                <p class="text-muted small mb-4">These details identify the service to students and visitors.</p>

                <div class="mb-3">
                    <label for="app_name" class="form-label fw-semibold">Application Name</label>
                    <input id="app_name" name="app_name" type="text" class="form-control" value="{{ old('app_name', $settings['app_name']) }}" required>
                </div>
                <div class="mb-3">
                    <label for="contact_email" class="form-label fw-semibold">Dispatch Email</label>
                    <input id="contact_email" name="contact_email" type="email" class="form-control" value="{{ old('contact_email', $settings['contact_email']) }}" required>
                </div>
                <div class="mb-0">
                    <label for="contact_phone" class="form-label fw-semibold">Dispatch Phone</label>
                    <input id="contact_phone" name="contact_phone" type="text" class="form-control" value="{{ old('contact_phone', $settings['contact_phone']) }}" required>
                </div>
            </section>
        </div>

        <div class="col-lg-6">
            <section class="card settings-card h-100 p-4 p-lg-5 bg-white">
                <h2 class="h5 fw-bold mb-1"><i class="bi bi-cash-coin me-2"></i>Fares & Hours</h2>
                <p class="text-muted small mb-4">Fares apply to new ride requests after saving.</p>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="base_fare" class="form-label fw-semibold">Standard Fare</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input id="base_fare" name="base_fare" type="number" min="0" step="0.01" class="form-control" value="{{ old('base_fare', $settings['base_fare']) }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="priority_fare" class="form-label fw-semibold">Priority Fare</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input id="priority_fare" name="priority_fare" type="number" min="0" step="0.01" class="form-control" value="{{ old('priority_fare', $settings['priority_fare']) }}" required>
                        </div>
                    </div>
                </div>
                <div class="mb-0">
                    <label for="operating_hours" class="form-label fw-semibold">Operating Hours</label>
                    <textarea id="operating_hours" name="operating_hours" rows="4" class="form-control" required>{{ old('operating_hours', $settings['operating_hours']) }}</textarea>
                </div>
            </section>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-success px-4"><i class="bi bi-check2 me-1"></i> Save Settings</button>
        </div>
    </form>
</main>
</body>
</html>
