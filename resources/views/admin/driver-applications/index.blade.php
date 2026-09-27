<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Applications — CampusRide Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8F9FA; color: #212529; min-height: 100vh; }
        .navbar-campus { background: #064E3B; }
        .fleet-card { border: 0; border-radius: 1rem; box-shadow: 0 8px 24px rgba(6, 78, 59, .08); }
        .badge-pending { background-color: #FEF3C7; color: #92400E; }
        .badge-approved { background-color: #D1FAE5; color: #065F46; }
        .badge-rejected { background-color: #FEE2E2; color: #991B1B; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-campus py-2 sticky-top shadow-sm">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('admin.dashboard') }}">
            <span class="badge bg-warning text-dark rounded-3 p-2"><i class="bi bi-person-badge-fill"></i></span>
            <span>Campus<span class="text-warning">Ride</span></span>
            <span class="badge bg-danger ms-2">Admin Command</span>
        </a>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
            <a href="{{ route('admin.drivers.index') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-ev-front me-1"></i> Driver Fleet</a>
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
            <div class="d-flex align-items-center gap-2">
                <h1 class="h3 fw-bold mb-1">Driver Applications</h1>
                @if ($counts['pending'] > 0)
                    <span class="badge bg-warning text-dark rounded-pill">{{ $counts['pending'] }} New</span>
                @endif
            </div>
            <p class="text-muted small mb-0">Review public driver submissions, verify licenses, and activate campus driver accounts.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('driver.apply') }}" target="_blank" class="btn btn-outline-secondary">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Public Application Form
            </a>
            <a href="{{ route('admin.drivers.create') }}" class="btn btn-success fw-semibold">
                <i class="bi bi-person-plus-fill me-1"></i> Add Driver Manually
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm" role="alert"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif

    <!-- Status Tabs -->
    <div class="mb-3 d-flex flex-wrap gap-2">
        <a href="{{ route('admin.driver-applications.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-white border text-dark' }}">
            <i class="bi bi-hourglass-split me-1"></i> Pending Applications ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.driver-applications.index', ['status' => 'approved']) }}" class="btn btn-sm {{ $status === 'approved' ? 'btn-success fw-bold' : 'btn-white border text-dark' }}">
            <i class="bi bi-check-circle me-1"></i> Approved ({{ $counts['approved'] }})
        </a>
        <a href="{{ route('admin.driver-applications.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-danger fw-bold' : 'btn-white border text-dark' }}">
            <i class="bi bi-x-circle me-1"></i> Rejected ({{ $counts['rejected'] }})
        </a>
        <a href="{{ route('admin.driver-applications.index', ['status' => '']) }}" class="btn btn-sm {{ empty($status) ? 'btn-dark fw-bold' : 'btn-white border text-dark' }}">
            All Submissions ({{ $counts['total'] }})
        </a>
    </div>

    <!-- Search Card -->
    <div class="card fleet-card bg-white p-3 mb-4">
        <form method="GET" action="{{ route('admin.driver-applications.index') }}" class="row g-2 align-items-center">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="search" name="search" class="form-control border-start-0" value="{{ $search }}" placeholder="Search applicant name, Gmail, phone, license, or vehicle model...">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">Search</button>
                @if ($search)
                    <a href="{{ route('admin.driver-applications.index', ['status' => $status]) }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Applications Table -->
    <div class="card fleet-card bg-white overflow-hidden">
        @if ($applications->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-folder-x fs-1 d-block mb-3 text-secondary"></i>
                <h5 class="fw-bold">No applications found</h5>
                <p class="small mb-3">No driver applications matching your current filter.</p>
                @if ($search || $status !== 'pending')
                    <a href="{{ route('admin.driver-applications.index') }}" class="btn btn-sm btn-outline-primary">View Pending Applications</a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th class="ps-4">Applicant</th>
                            <th>Contact</th>
                            <th>License & Experience</th>
                            <th>Vehicle Requested</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $app)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $app->full_name }}</div>
                                    <span class="text-muted small">ID: #APP-{{ str_pad($app->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="small fw-semibold"><i class="bi bi-envelope text-primary me-1"></i>{{ $app->email }}</div>
                                    <div class="small text-muted"><i class="bi bi-telephone text-secondary me-1"></i>{{ $app->phone }}</div>
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ $app->license_number }}</div>
                                    <span class="badge bg-light text-dark border">{{ $app->experience_years ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <div class="small fw-semibold">{{ $app->vehicle_model }}</div>
                                    <span class="badge bg-secondary-subtle text-secondary small">{{ $app->vehicle_type }} • {{ $app->plate_number }}</span>
                                </td>
                                <td class="small text-muted">
                                    {{ $app->created_at->format('M d, Y') }}<br>
                                    <span class="text-muted" style="font-size: 0.75rem;">{{ $app->created_at->diffForHumans() }}</span>
                                </td>
                                <td>
                                    @if ($app->status === 'pending')
                                        <span class="badge badge-pending px-2 py-1"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                    @elseif ($app->status === 'approved')
                                        <span class="badge badge-approved px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Approved</span>
                                    @else
                                        <span class="badge badge-rejected px-2 py-1"><i class="bi bi-x-circle-fill me-1"></i>Rejected</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.driver-applications.show', $app) }}" class="btn btn-sm btn-outline-dark">
                                        <i class="bi bi-eye me-1"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($applications->hasPages())
                <div class="p-3 border-top">
                    {{ $applications->links() }}
                </div>
            @endif
        @endif
    </div>
</main>
</body>
</html>
