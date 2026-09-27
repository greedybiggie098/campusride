<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Inquiries — CampusRide Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F8F9FA; color: #212529; min-height: 100vh; }
        .navbar-campus { background: #0F5132; }
        .inquiry-card { border: 0; border-radius: 1rem; box-shadow: 0 8px 24px rgba(15, 81, 50, .08); }
        .message-preview { max-width: 360px; white-space: pre-wrap; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-campus py-2 sticky-top shadow-sm">
    <div class="container-fluid px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('admin.dashboard') }}">
            <span class="badge bg-warning text-dark rounded-3 p-2"><i class="bi bi-shield-shaded"></i></span>
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
            <h1 class="h3 fw-bold mb-1">Contact Inquiries</h1>
            <p class="text-muted small mb-0">Review and manage messages sent to CampusRide dispatch.</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-success btn-sm"><i class="bi bi-globe me-1"></i> View Public Site</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card inquiry-card bg-white overflow-hidden">
        @if ($messages->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-chat-left-text fs-1 d-block mb-3"></i>
                <h5 class="fw-bold">No inquiries yet</h5>
                <p class="small mb-0">Messages submitted through the contact form will appear here.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Sender</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                            @php
                                $statusClass = match ($message->status) {
                                    'resolved' => 'success',
                                    'in_progress' => 'warning text-dark',
                                    default => 'danger',
                                };
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $message->name }}</div>
                                    <a class="small text-muted text-decoration-none" href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                                    @if ($message->phone)
                                        <div class="small text-muted">{{ $message->phone }}</div>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $message->subject }}</td>
                                <td><div class="message-preview small text-muted">{{ $message->message }}</div></td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $message->status)) }}</span>
                                </td>
                                <td class="small text-muted">{{ $message->created_at->format('M d, Y h:i A') }}</td>
                                <td class="text-end pe-4">
                                    <form method="POST" action="{{ route('admin.messages.status', $message) }}" class="d-inline-flex align-items-center gap-1 mb-1">
                                        @csrf
                                        @method('PATCH')
                                        <label for="status-{{ $message->id }}" class="visually-hidden">Update status</label>
                                        <select id="status-{{ $message->id }}" name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            @foreach (['new' => 'New', 'in_progress' => 'In Progress', 'resolved' => 'Resolved'] as $value => $label)
                                                <option value="{{ $value }}" @selected($message->status === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                    <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" class="d-inline" onsubmit="return confirm('Delete this inquiry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete inquiry"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($messages->hasPages())
                <div class="p-3 border-top">{{ $messages->links() }}</div>
            @endif
        @endif
    </div>
</main>
</body>
</html>
