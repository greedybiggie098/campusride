<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Rides — CampusRide Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand fw-bold">🚌 CampusRide Admin</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white small">{{ auth()->user()->name }}</span>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-danger">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container py-5">

    <h4 class="fw-bold mb-4">🚗 All Ride Requests</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    @if ($rides->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <div class="fs-1">📋</div>
                <p class="mt-2">No ride requests yet.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Passenger</th>
                            <th>Pickup</th>
                            <th>Drop-off</th>
                            <th>Driver</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rides as $ride)
                        <tr>
                            <td>{{ $ride->id }}</td>
                            <td>{{ $ride->passenger->user->name ?? '—' }}</td>
                            <td>{{ $ride->pickup_location }}</td>
                            <td>{{ $ride->dropoff_location }}</td>
                            <td>
                                @if ($ride->driver)
                                    {{ $ride->driver->user->name }}
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
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
                                <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $ride->status)) }}</span>
                            </td>
                            <td>{{ $ride->requested_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.rides.show', $ride) }}" class="btn btn-sm btn-outline-dark">Manage</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">{{ $rides->links() }}</div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
