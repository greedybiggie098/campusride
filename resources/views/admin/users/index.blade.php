<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students & Users — CampusRide Admin</title>
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
            background-color: #0f172a;
        }
    </style>
</head>
<body>

<!-- Admin Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-campus py-2 sticky-top shadow-sm">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-white" href="{{ route('admin.dashboard') }}">
            <span class="badge bg-primary rounded-3 p-2 text-white"><i class="bi bi-shield-shaded"></i></span>
            <span>Campus<span class="text-info">Ride</span></span>
            <span class="badge bg-danger ms-2">Admin Command</span>
        </a>

        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="{{ route('admin.rides.index') }}">
                        <i class="bi bi-car-front me-1"></i> Ride Dispatch
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-bold text-info" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people me-1"></i> Students & Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="{{ route('admin.drivers.index') }}">
                        <i class="bi bi-ev-front me-1"></i> Drivers Fleet
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="{{ route('admin.messages.index') }}">
                        <i class="bi bi-chat-left-text me-1"></i> Inquiries
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <span class="text-white-50 small">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<main class="container-fluid px-lg-4 py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1 text-dark">Students & Users Management</h1>
            <p class="text-muted small mb-0">View registered students, academic departments, transit history, and account credentials</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary fw-semibold shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Add New Student / User
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email, student ID, course..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    <option value="passenger" {{ $roleFilter === 'passenger' ? 'selected' : '' }}>Students (Passengers)</option>
                    <option value="driver" {{ $roleFilter === 'driver' ? 'selected' : '' }}>Drivers</option>
                    <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Administrators</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark">Filter</button>
                @if ($search || $roleFilter)
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-body p-0">
            @if ($users->isEmpty())
                <div class="text-center py-5 text-muted">
                    <div class="fs-1 mb-2">👥</div>
                    <h5 class="fw-bold">No users found</h5>
                    <p class="small text-muted mb-3">No user records matched your filter criteria.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4"># ID</th>
                                <th>Name & Email</th>
                                <th>Role</th>
                                <th>Student ID / Info</th>
                                <th>Phone</th>
                                <th>Joined</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $u)
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#{{ $u->id }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $u->name }}</div>
                                        <div class="small text-muted">{{ $u->email }}</div>
                                    </td>
                                    <td>
                                        @if ($u->role === 'admin')
                                            <span class="badge bg-danger">Admin</span>
                                        @elseif ($u->role === 'driver')
                                            <span class="badge bg-info text-dark">Driver</span>
                                        @else
                                            <span class="badge bg-primary">Student</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($u->passenger)
                                            <div class="fw-semibold text-dark">{{ $u->passenger->student_id ?? 'No ID' }}</div>
                                            <div class="small text-muted">{{ $u->passenger->course ?? 'General' }} ({{ $u->passenger->year_level ?? '1st Year' }})</div>
                                        @elseif ($u->driver)
                                            <div class="fw-semibold text-dark">{{ $u->driver->vehicle_model }}</div>
                                            <div class="small text-muted">Plate: {{ $u->driver->plate_number }}</div>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="small">{{ $u->phone ?? '—' }}</td>
                                    <td class="small text-muted">{{ $u->created_at->format('M d, Y') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-outline-info" title="View Profile">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-secondary" title="Edit Profile">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if ($u->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline" onsubmit="return confirm('Delete this user account permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete User">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
