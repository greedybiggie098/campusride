<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ride #{{ $ride->id }} — CampusRide Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand fw-bold">🚌 CampusRide Admin</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white small">{{ auth()->user()->name }}</span>
            <a href="{{ route('admin.rides.index') }}" class="btn btn-sm btn-outline-light">All Rides</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-danger">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row g-4">

        {{-- Ride Details --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Ride #{{ $ride->id }} Details</h5>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    @php
                        $badge = match($ride->status) {
                            'pending'     => 'warning',
                            'assigned'    => 'info',
                            'accepted'    => 'warning',
                            'in_progress' => 'primary',
                            'completed'   => 'success',
                            'cancelled'   => 'danger',
                            default       => 'secondary',
                        };
                    @endphp

                    <span class="badge bg-{{ $badge }} mb-3 fs-6">{{ ucfirst(str_replace('_', ' ', $ride->status)) }}</span>

                    <dl class="row mb-0">
                        <dt class="col-sm-5">Passenger</dt>
                        <dd class="col-sm-7">{{ $ride->passenger->user->name ?? '—' }}</dd>

                        <dt class="col-sm-5">Pickup</dt>
                        <dd class="col-sm-7">{{ $ride->pickup_location }}</dd>

                        <dt class="col-sm-5">Drop-off</dt>
                        <dd class="col-sm-7">{{ $ride->dropoff_location }}</dd>

                        <dt class="col-sm-5">Requested</dt>
                        <dd class="col-sm-7">{{ $ride->requested_at->format('M d, Y h:i A') }}</dd>

                        @if ($ride->pickup_at)
                        <dt class="col-sm-5">Picked up</dt>
                        <dd class="col-sm-7">{{ $ride->pickup_at->format('M d, Y h:i A') }}</dd>
                        @endif

                        @if ($ride->completed_at)
                        <dt class="col-sm-5">Completed</dt>
                        <dd class="col-sm-7">{{ $ride->completed_at->format('M d, Y h:i A') }}</dd>
                        @endif

                        @if ($ride->notes)
                        <dt class="col-sm-5">Notes</dt>
                        <dd class="col-sm-7">{{ $ride->notes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="col-md-6">

            {{-- Assign Driver --}}
            @if (in_array($ride->status, ['pending', 'assigned', 'accepted']))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Assign Driver</h5>

                    @if ($drivers->isEmpty())
                        <div class="alert alert-warning mb-0">No available drivers at the moment.</div>
                    @else
                        <form method="POST" action="{{ route('admin.rides.assign', $ride) }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="driver_id" class="form-label fw-semibold">Select Driver</label>
                                <select
                                    name="driver_id"
                                    id="driver_id"
                                    class="form-select @error('driver_id') is-invalid @enderror"
                                    required
                                >
                                    <option value="">— Choose a driver —</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            {{ $ride->driver_id == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->user->name }}
                                            @if ($driver->vehicle_model)
                                                ({{ $driver->vehicle_model }} — {{ $driver->plate_number }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-dark px-4">Assign Driver</button>
                        </form>
                    @endif
                </div>
            </div>
            @endif

            {{-- Update Ride Status --}}
            @if (!in_array($ride->status, ['completed', 'cancelled']))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Update Status</h5>
                    <form method="POST" action="{{ route('admin.rides.status', $ride) }}" class="d-flex gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="form-select" required>
                            @foreach (['pending', 'assigned', 'accepted', 'in_progress', 'completed'] as $status)
                                <option value="{{ $status }}" {{ $ride->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-dark text-nowrap">Update</button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Cancel Ride --}}
            @if (!in_array($ride->status, ['completed', 'cancelled']))
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-danger">Cancel Ride</h5>
                    <p class="text-muted small mb-3">This will cancel the ride and free up the assigned driver (if any).</p>
                    <form method="POST" action="{{ route('admin.rides.cancel', $ride) }}">
                        @csrf
                        @method('PATCH')
                        <button
                            type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Cancel this ride?')"
                        >
                            Cancel Ride
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.rides.index') }}" class="btn btn-outline-secondary">← Back to All Rides</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
