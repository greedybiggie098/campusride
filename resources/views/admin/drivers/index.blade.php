<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Fleet — CampusRide Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8F9FA; color: #212529; min-height: 100vh; }
        .navbar-campus { background: #0F5132; }
        .fleet-card { border: 0; border-radius: 1rem; box-shadow: 0 8px 24px rgba(15, 81, 50, .08); }
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
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
            <span class="text-white-50 small d-none d-md-inline">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-warning">Logout</button>
            </form>
        </div>
    </div>
</nav>

<main class="container-fluid px-lg-4 py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Driver Fleet</h1>
            <p class="text-muted small mb-0">Manage campus drivers, vehicles, and availability.</p>
        </div>
        <a href="{{ route('admin.drivers.create') }}" class="btn btn-success fw-semibold"><i class="bi bi-person-plus-fill me-1"></i> Add Driver</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm" role="alert"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif

    <div class="card fleet-card bg-white p-3 mb-4">
        <form method="GET" action="{{ route('admin.drivers.index') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
                <label for="search" class="visually-hidden">Search drivers</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input id="search" type="search" name="search" class="form-control border-start-0" value="{{ $search }}" placeholder="Search driver, email, plate, or vehicle...">
                </div>
            </div>
            <div class="col-md-3">
                <label for="availability" class="visually-hidden">Filter availability</label>
                <select id="availability" name="availability" class="form-select">
                    <option value="">All Availability</option>
                    @foreach (['available' => 'Available', 'on_trip' => 'On Trip', 'off_duty' => 'Off Duty'] as $value => $label)
                        <option value="{{ $value }}" @selected($availFilter === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark">Filter</button>
                @if ($search || $availFilter)
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="card fleet-card bg-white overflow-hidden">
        @if ($drivers->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-bus-front fs-1 d-block mb-3"></i>
                <h5 class="fw-bold">No drivers found</h5>
                <p class="small mb-0">Try changing your filters or add a new driver.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Driver</th>
                            <th>Vehicle</th>
                            <th>License / Plate</th>
                            <th>Availability</th>
                            <th>Status</th>
                            <th>Trips</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($drivers as $driver)
                            @php
                                $availabilityClass = match ($driver->availability) {
                                    'available' => 'success',
                                    'on_trip' => 'primary',
                                    default => 'secondary',
                                };
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $driver->user->name }}</div>
                                    <a href="mailto:{{ $driver->user->email }}" class="small text-muted text-decoration-none">{{ $driver->user->email }}</a>
                                    @if ($driver->user->phone)
                                        <div class="small text-muted">{{ $driver->user->phone }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $driver->vehicle_model ?: 'Vehicle not specified' }}</div>
                                    <div class="small text-muted">{{ $driver->vehicle_type ?: 'Type not specified' }}</div>
                                </td>
                                <td>
                                    <div class="small">{{ $driver->license_number ?: 'No license' }}</div>
                                    <span class="badge bg-light text-dark border">{{ $driver->plate_number ?: 'No plate' }}</span>
                                </td>
                                <td><span class="badge bg-{{ $availabilityClass }}">{{ ucfirst(str_replace('_', ' ', $driver->availability)) }}</span></td>
                                <td><span class="badge bg-{{ $driver->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($driver->status) }}</span></td>
                                <td class="fw-semibold">{{ $driver->rides->count() }}</td>
                                <td class="text-end pe-4 text-nowrap">
                                    <a href="{{ route('admin.drivers.show', $driver) }}" class="btn btn-sm btn-outline-success" title="View driver"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.drivers.edit', $driver) }}" class="btn btn-sm btn-outline-secondary" title="Edit driver"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.drivers.destroy', $driver) }}" class="d-inline" onsubmit="return confirm('Remove this driver from the fleet?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove driver"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($drivers->hasPages())
                <div class="p-3 border-top">{{ $drivers->links() }}</div>
            @endif
        @endif
    </div>
</main>
</body>
</html>
