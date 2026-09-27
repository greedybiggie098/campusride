<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application #APP-{{ str_pad($application->id, 4, '0', STR_PAD_LEFT) }} — CampusRide Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8F9FA; color: #212529; min-height: 100vh; }
        .navbar-campus { background: #064E3B; }
        .detail-card { border: 0; border-radius: 1rem; box-shadow: 0 8px 24px rgba(6, 78, 59, .08); }
        .badge-pending { background-color: #FEF3C7; color: #92400E; font-size: 0.9rem; }
        .badge-approved { background-color: #D1FAE5; color: #065F46; font-size: 0.9rem; }
        .badge-rejected { background-color: #FEE2E2; color: #991B1B; font-size: 0.9rem; }
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
            <a href="{{ route('admin.driver-applications.index') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-arrow-left me-1"></i> Back to Applications</a>
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
            <div class="d-flex align-items-center gap-3 mb-1">
                <h1 class="h3 fw-bold mb-0">{{ $application->full_name }}</h1>
                @if ($application->status === 'pending')
                    <span class="badge badge-pending px-3 py-1"><i class="bi bi-hourglass-split me-1"></i>Pending Review</span>
                @elseif ($application->status === 'approved')
                    <span class="badge badge-approved px-3 py-1"><i class="bi bi-check-circle-fill me-1"></i>Approved & Active</span>
                @else
                    <span class="badge badge-rejected px-3 py-1"><i class="bi bi-x-circle-fill me-1"></i>Rejected</span>
                @endif
            </div>
            <p class="text-muted small mb-0">Application Ref: #APP-{{ str_pad($application->id, 4, '0', STR_PAD_LEFT) }} • Submitted {{ $application->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.driver-applications.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> All Applications
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <!-- Applicant Details Column -->
        <div class="col-lg-7">
            <div class="card detail-card bg-white p-4 mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-3 mb-3">
                    <i class="bi bi-person-lines-fill text-primary me-2"></i>Applicant Credentials
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Full Name</label>
                        <p class="fs-6 fw-bold mb-0 text-dark">{{ $application->full_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Gmail Address</label>
                        <p class="fs-6 fw-semibold mb-0 text-primary">{{ $application->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Phone Number</label>
                        <p class="fs-6 mb-0">{{ $application->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Driving Experience</label>
                        <p class="fs-6 mb-0">{{ $application->experience_years ?? 'Not specified' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Driver's License Number</label>
                        <p class="fs-6 fw-bold text-dark mb-0">{{ $application->license_number }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Submission Date</label>
                        <p class="fs-6 mb-0">{{ $application->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                <h5 class="fw-bold text-dark border-bottom pb-3 mt-4 mb-3">
                    <i class="bi bi-car-front-fill text-success me-2"></i>Vehicle Specifications
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">Vehicle Type</label>
                        <p class="fs-6 mb-0"><span class="badge bg-secondary-subtle text-secondary px-2 py-1">{{ $application->vehicle_type }}</span></p>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">Make & Model</label>
                        <p class="fs-6 fw-semibold mb-0">{{ $application->vehicle_model }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">License Plate</label>
                        <p class="fs-6 fw-bold mb-0 text-dark">{{ $application->plate_number }}</p>
                    </div>
                </div>

                @if ($application->notes)
                    <h5 class="fw-bold text-dark border-bottom pb-3 mt-4 mb-3">
                        <i class="bi bi-chat-quote-fill text-info me-2"></i>Applicant Notes & Availability
                    </h5>
                    <div class="p-3 bg-light rounded-3 small">
                        {{ $application->notes }}
                    </div>
                @endif

                @if ($application->admin_notes)
                    <h5 class="fw-bold text-dark border-bottom pb-3 mt-4 mb-3">
                        <i class="bi bi-journal-text text-danger me-2"></i>Operations Review Notes
                    </h5>
                    <div class="p-3 bg-light rounded-3 small border-start border-4 border-primary">
                        {{ $application->admin_notes }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Action / Approval Column -->
        <div class="col-lg-5">
            @if ($application->status === 'pending')
                <!-- Approval Card -->
                <div class="card detail-card bg-white p-4 mb-4 border-top border-4 border-success">
                    <h5 class="fw-bold text-success mb-2">
                        <i class="bi bi-check-circle-fill me-1"></i> Approve & Activate Account
                    </h5>
                    <p class="text-muted small mb-3">
                        Approving this application will automatically generate a driver account for <strong>{{ $application->email }}</strong> and add their vehicle to the active CampusRide fleet.
                    </p>

                    <form method="POST" action="{{ route('admin.driver-applications.approve', $application) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label small fw-bold text-muted">Initial Account Password</label>
                            <input type="text" id="password" name="password" class="form-control" value="password" required>
                            <div class="form-text small">Default is <code>password</code>. The driver can change it after login.</div>
                        </div>

                        <div class="mb-3">
                            <label for="vehicle_type" class="form-label small fw-bold text-muted">Confirmed Vehicle Type</label>
                            <input type="text" id="vehicle_type" name="vehicle_type" class="form-control" value="{{ $application->vehicle_type }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="vehicle_model" class="form-label small fw-bold text-muted">Confirmed Make & Model</label>
                            <input type="text" id="vehicle_model" name="vehicle_model" class="form-control" value="{{ $application->vehicle_model }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="plate_number" class="form-label small fw-bold text-muted">Confirmed License Plate</label>
                            <input type="text" id="plate_number" name="plate_number" class="form-control" value="{{ $application->plate_number }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="admin_notes" class="form-label small fw-bold text-muted">Admin Approval Notes (Optional)</label>
                            <textarea id="admin_notes" name="admin_notes" class="form-control" rows="2" placeholder="e.g. Verified valid campus driver permit on file."></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-semibold py-2">
                            <i class="bi bi-check-lg me-1"></i> Approve & Provision Driver
                        </button>
                    </form>
                </div>

                <!-- Rejection Card -->
                <div class="card detail-card bg-white p-4">
                    <h6 class="fw-bold text-danger mb-2">
                        <i class="bi bi-x-circle me-1"></i> Reject Application
                    </h6>
                    <p class="text-muted small mb-3">Mark this application as rejected if criteria are not met.</p>
                    
                    <form method="POST" action="{{ route('admin.driver-applications.reject', $application) }}" onsubmit="return confirm('Are you sure you want to reject this driver application?');">
                        @csrf
                        <div class="mb-3">
                            <label for="reject_notes" class="form-label small fw-bold text-muted">Rejection Reason</label>
                            <textarea id="reject_notes" name="admin_notes" class="form-control" rows="2" placeholder="e.g. License expired or vehicle type does not match campus guidelines."></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-danger w-100 btn-sm fw-semibold">
                            <i class="bi bi-x-lg me-1"></i> Reject Application
                        </button>
                    </form>
                </div>
            @elseif ($application->status === 'approved')
                <div class="card detail-card bg-white p-4 text-center">
                    <div class="text-success fs-1 mb-2">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Approved Driver</h5>
                    <p class="text-muted small mb-3">
                        This application was approved on {{ $application->reviewed_at ? $application->reviewed_at->format('M d, Y h:i A') : 'N/A' }}.
                    </p>
                    @if ($application->user)
                        <div class="p-3 bg-light rounded-3 text-start small mb-3">
                            <div><strong>Driver Name:</strong> {{ $application->user->name }}</div>
                            <div><strong>Driver Email:</strong> {{ $application->user->email }}</div>
                            <div><strong>Status:</strong> Active in Fleet</div>
                        </div>
                        @if ($application->user->driver)
                            <a href="{{ route('admin.drivers.edit', $application->user->driver) }}" class="btn btn-dark w-100">
                                <i class="bi bi-pencil-square me-1"></i> Manage in Driver Fleet
                            </a>
                        @endif
                    @endif
                </div>
            @else
                <div class="card detail-card bg-white p-4 text-center">
                    <div class="text-danger fs-1 mb-2">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Application Rejected</h5>
                    <p class="text-muted small mb-0">
                        Rejected on {{ $application->reviewed_at ? $application->reviewed_at->format('M d, Y h:i A') : 'N/A' }}.
                    </p>
                </div>
            @endif
        </div>
    </div>
</main>
</body>
</html>
