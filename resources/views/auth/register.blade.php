@extends('layouts.public')

@section('title', 'Create Account — CampusRide')

@section('content')
<div class="py-5 bg-light flex-grow-1 d-flex align-items-center">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">

                    <!-- Role Switcher Segmented Control -->
                    @php
                        $activeRole = old('account_type', $selectedRole ?? 'student');
                    @endphp
                    <div class="role-selector-wrap p-1 rounded-pill bg-light border mb-4 d-flex">
                        <button type="button" 
                                id="btn-role-student" 
                                class="btn flex-fill rounded-pill py-2 fw-bold text-center transition {{ $activeRole === 'student' ? 'btn-success shadow-sm' : 'text-muted' }}"
                                onclick="switchRole('student')">
                            <i class="bi bi-mortarboard-fill me-1"></i> Create Account for Students
                        </button>
                        <button type="button" 
                                id="btn-role-driver" 
                                class="btn flex-fill rounded-pill py-2 fw-bold text-center transition {{ $activeRole === 'driver' ? 'btn-warning text-dark shadow-sm' : 'text-muted' }}"
                                onclick="switchRole('driver')">
                            <i class="bi bi-car-front-fill me-1"></i> Create Account for Drivers
                        </button>
                    </div>

                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div id="header-badge" class="navbar-brand-badge mx-auto mb-2" style="width: 52px; height: 52px; font-size: 1.6rem;">
                            <i id="header-icon" class="bi {{ $activeRole === 'driver' ? 'bi-car-front-fill' : 'bi-mortarboard-fill' }}"></i>
                        </div>
                        <h3 id="header-title" class="fw-bold text-dark mb-1">
                            {{ $activeRole === 'driver' ? 'Create Account for Drivers' : 'Create Account for Students' }}
                        </h3>
                        <p id="header-subtitle" class="text-muted small mb-0">
                            {{ $activeRole === 'driver' ? 'Register your driver credentials & vehicle details to start driving' : 'Register with your student details for seamless campus ride access' }}
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger small border-0 shadow-sm mb-4 rounded-3">
                            <h6 class="fw-bold mb-1 small"><i class="bi bi-exclamation-triangle-fill me-1"></i> Please check the required fields:</h6>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="account_type" id="account_type" value="{{ $activeRole }}">

                        <div class="row g-3">
                            <!-- Shared Fields -->
                            <div class="col-md-6">
                                <label for="full_name" class="form-label small fw-bold text-muted">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name', old('name')) }}" required autofocus placeholder="e.g. Jordan Smith">
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label small fw-bold text-muted">Gmail Address <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="yourname@gmail.com" autocomplete="email">
                                <div class="form-text small">Must end with @gmail.com</div>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label small fw-bold text-muted">Phone Number <span id="phone-req" class="text-danger {{ $activeRole === 'driver' ? '' : 'd-none' }}">*</span></label>
                                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000" {{ $activeRole === 'driver' ? 'required' : '' }}>
                            </div>

                            <div class="col-md-6">
                                <label for="profile_picture" class="form-label small fw-bold text-muted">Profile Picture (Optional)</label>
                                <input type="file" id="profile_picture" name="profile_picture" class="form-control" accept="image/*">
                            </div>

                            <!-- ─── Student Specific Section ───────────────────────────── -->
                            <div id="student-fields" class="{{ $activeRole === 'student' ? '' : 'd-none' }} col-12">
                                <div class="p-3 bg-light rounded-3 border mb-2">
                                    <h6 class="fw-bold text-success mb-3 small">
                                        <i class="bi bi-mortarboard-fill me-1"></i> Student Academic Information
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="student_id" class="form-label small fw-bold text-muted">Student ID Number</label>
                                            <input type="text" id="student_id" name="student_id" class="form-control bg-white" value="{{ old('student_id') }}" placeholder="e.g. STU-2026-104">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="department" class="form-label small fw-bold text-muted">Faculty / Department</label>
                                            <select id="department" name="department" class="form-select bg-white">
                                                <option value="Sciences & Tech" {{ old('department') == 'Sciences & Tech' ? 'selected' : '' }}>Sciences & Tech</option>
                                                <option value="Engineering" {{ old('department') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                                <option value="Business & Economics" {{ old('department') == 'Business & Economics' ? 'selected' : '' }}>Business & Economics</option>
                                                <option value="Health & Medicine" {{ old('department') == 'Health & Medicine' ? 'selected' : '' }}>Health & Medicine</option>
                                                <option value="Arts & Humanities" {{ old('department') == 'Arts & Humanities' ? 'selected' : '' }}>Arts & Humanities</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="course" class="form-label small fw-bold text-muted">Major / Course</label>
                                            <input type="text" id="course" name="course" class="form-control bg-white" value="{{ old('course') }}" placeholder="e.g. Computer Science">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="year_level" class="form-label small fw-bold text-muted">Year Level</label>
                                            <select id="year_level" name="year_level" class="form-select bg-white">
                                                <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                                <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                                <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                                <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                                <option value="Postgraduate" {{ old('year_level') == 'Postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ─── Driver Specific Section ────────────────────────────── -->
                            <div id="driver-fields" class="{{ $activeRole === 'driver' ? '' : 'd-none' }} col-12">
                                <div class="p-3 bg-warning bg-opacity-10 rounded-3 border border-warning mb-2">
                                    <h6 class="fw-bold text-dark mb-3 small">
                                        <i class="bi bi-car-front-fill me-1 text-warning"></i> Driver & Vehicle Information
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="license_number" class="form-label small fw-bold text-muted">Driver's License Number <span class="text-danger">*</span></label>
                                            <input type="text" id="license_number" name="license_number" class="form-control bg-white" value="{{ old('license_number') }}" placeholder="e.g. DL-2026-4482">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="vehicle_type" class="form-label small fw-bold text-muted">Vehicle Type <span class="text-danger">*</span></label>
                                            <select id="vehicle_type" name="vehicle_type" class="form-select bg-white">
                                                <option value="Van" {{ old('vehicle_type') == 'Van' ? 'selected' : '' }}>Van (Passenger Shuttle)</option>
                                                <option value="Minibus Shuttle" {{ old('vehicle_type') == 'Minibus Shuttle' ? 'selected' : '' }}>Minibus Shuttle</option>
                                                <option value="Electric Cart" {{ old('vehicle_type') == 'Electric Cart' ? 'selected' : '' }}>Electric Campus Cart</option>
                                                <option value="Sedan" {{ old('vehicle_type') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                                                <option value="SUV" {{ old('vehicle_type') == 'SUV' ? 'selected' : '' }}>SUV</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="vehicle_model" class="form-label small fw-bold text-muted">Vehicle Make & Model <span class="text-danger">*</span></label>
                                            <input type="text" id="vehicle_model" name="vehicle_model" class="form-control bg-white" value="{{ old('vehicle_model') }}" placeholder="e.g. Toyota HiAce (White)">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="plate_number" class="form-label small fw-bold text-muted">License Plate Number <span class="text-danger">*</span></label>
                                            <input type="text" id="plate_number" name="plate_number" class="form-control bg-white" value="{{ old('plate_number') }}" placeholder="e.g. CR-8899-XY">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Passwords -->
                            <div class="col-md-6">
                                <label for="password" class="form-label small fw-bold text-muted">Password <span class="text-danger">*</span></label>
                                <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password" placeholder="At least 8 characters">
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label small fw-bold text-muted">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password" placeholder="Repeat password">
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12 mt-4">
                                <button type="submit" id="submit-btn" class="btn {{ $activeRole === 'driver' ? 'btn-warning text-dark' : 'btn-cr-accent' }} w-100 py-2 fw-semibold fs-6">
                                    <span id="submit-btn-text">{{ $activeRole === 'driver' ? 'Create Driver Account' : 'Create Student Account' }}</span>
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Footer links -->
                    <div class="text-center pt-3 mt-3 border-top">
                        <span class="text-muted small">Already have an account?</span>
                        <a href="{{ route('login') }}" class="fw-bold text-primary small text-decoration-none ms-1">Sign In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchRole(role) {
    const accountTypeInput = document.getElementById('account_type');
    const studentBtn = document.getElementById('btn-role-student');
    const driverBtn = document.getElementById('btn-role-driver');
    const studentFields = document.getElementById('student-fields');
    const driverFields = document.getElementById('driver-fields');
    const headerTitle = document.getElementById('header-title');
    const headerSubtitle = document.getElementById('header-subtitle');
    const headerIcon = document.getElementById('header-icon');
    const submitBtn = document.getElementById('submit-btn');
    const submitBtnText = document.getElementById('submit-btn-text');
    const phoneInput = document.getElementById('phone');
    const phoneReq = document.getElementById('phone-req');
    const licenseInput = document.getElementById('license_number');
    const vehicleTypeInput = document.getElementById('vehicle_type');
    const vehicleModelInput = document.getElementById('vehicle_model');
    const plateNumberInput = document.getElementById('plate_number');

    accountTypeInput.value = role;

    if (role === 'driver') {
        // Driver UI state
        studentBtn.className = 'btn flex-fill rounded-pill py-2 fw-bold text-center text-muted';
        driverBtn.className = 'btn flex-fill rounded-pill py-2 fw-bold text-center btn-warning text-dark shadow-sm';
        
        studentFields.classList.add('d-none');
        driverFields.classList.remove('d-none');

        headerTitle.textContent = 'Create Account for Drivers';
        headerSubtitle.textContent = 'Register your driver credentials & vehicle details to start driving';
        headerIcon.className = 'bi bi-car-front-fill';

        submitBtn.className = 'btn btn-warning text-dark w-100 py-2 fw-semibold fs-6';
        submitBtnText.textContent = 'Create Driver Account';

        phoneInput.required = true;
        phoneReq.classList.remove('d-none');
        if (licenseInput) licenseInput.required = true;
        if (vehicleTypeInput) vehicleTypeInput.required = true;
        if (vehicleModelInput) vehicleModelInput.required = true;
        if (plateNumberInput) plateNumberInput.required = true;
    } else {
        // Student UI state
        studentBtn.className = 'btn flex-fill rounded-pill py-2 fw-bold text-center btn-success shadow-sm';
        driverBtn.className = 'btn flex-fill rounded-pill py-2 fw-bold text-center text-muted';

        driverFields.classList.add('d-none');
        studentFields.classList.remove('d-none');

        headerTitle.textContent = 'Create Account for Students';
        headerSubtitle.textContent = 'Register with your student details for seamless campus ride access';
        headerIcon.className = 'bi bi-mortarboard-fill';

        submitBtn.className = 'btn btn-cr-accent w-100 py-2 fw-semibold fs-6';
        submitBtnText.textContent = 'Create Student Account';

        phoneInput.required = false;
        phoneReq.classList.add('d-none');
        if (licenseInput) licenseInput.required = false;
        if (vehicleTypeInput) vehicleTypeInput.required = false;
        if (vehicleModelInput) vehicleModelInput.required = false;
        if (plateNumberInput) plateNumberInput.required = false;
    }
}

// Ensure proper required states on initial load
document.addEventListener('DOMContentLoaded', function() {
    switchRole("{{ $activeRole }}");
});
</script>
@endsection
