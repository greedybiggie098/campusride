@extends('layouts.public')

@section('title', 'Apply to Drive — CampusRide Campus Transit')

@section('content')
<!-- Hero Section -->
<div class="py-5" style="background: linear-gradient(135deg, var(--cr-primary) 0%, var(--cr-primary-hover) 60%, var(--cr-dark) 100%); color: #ffffff;">
    <div class="container py-3">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark fw-bold px-3 py-1 mb-2">
                    <i class="bi bi-person-badge-fill me-1"></i> Driver Recruitment
                </span>
                <h1 class="display-5 fw-bold mb-2">Drive for CampusRide</h1>
                <p class="lead text-light text-opacity-75 mb-0">
                    Join our verified campus mobility network. Help students and faculty move safely across campus with flexible shifts and verified riders.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="d-inline-flex flex-column align-items-lg-end">
                    <span class="text-white-50 small">Already an approved driver?</span>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm mt-1">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Driver Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="py-5 bg-light flex-grow-1">
    <div class="container py-lg-3">
        <div class="row g-4 justify-content-center">
            <!-- Benefits & Info Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold mb-3 text-dark">
                        <i class="bi bi-stars text-warning me-2"></i>Why Drive With Us?
                    </h5>
                    
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-3 bg-success bg-opacity-10 text-success p-2 fs-5">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 small">Safe, Verified Passengers</h6>
                            <p class="text-muted small mb-0">All riders are authenticated university students and verified staff members.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-2 fs-5">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 small">Flexible Campus Shifts</h6>
                            <p class="text-muted small mb-0">Choose daytime shuttle hours or night-owl safety escorts to fit your schedule.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 text-warning p-2 fs-5">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 small">Dedicated Campus Routes</h6>
                            <p class="text-muted small mb-0">Familiar navigation connecting dorms, lecture halls, transit hubs, and libraries.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 bg-info bg-opacity-10 text-info p-2 fs-5">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 small">Quick Approval Process</h6>
                            <p class="text-muted small mb-0">Our operations team reviews submissions within 24–48 hours and sends account details directly to your Gmail.</p>
                        </div>
                    </div>
                </div>

                <!-- Assistance Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-question-circle text-primary me-2"></i>Need Assistance?</h6>
                    <p class="text-muted small mb-2">Have questions about vehicle requirements or driver guidelines?</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-envelope me-1"></i> Contact Dispatch Office
                    </a>
                </div>
            </div>

            <!-- Application Form Column -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
                    <div class="mb-4 pb-2 border-bottom">
                        <h4 class="fw-bold text-dark mb-1">Driver Application Form</h4>
                        <p class="text-muted small mb-0">Please fill out your verified personal, license, and vehicle information.</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center gap-3 p-3 mb-4 rounded-3">
                            <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Application Submitted!</h6>
                                <p class="small mb-0">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4 rounded-3">
                            <h6 class="fw-bold mb-1 small"><i class="bi bi-exclamation-triangle-fill me-1"></i> Please correct the following errors:</h6>
                            <ul class="mb-0 ps-3 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('driver.apply.submit') }}">
                        @csrf

                        <!-- Section 1: Personal Info -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-person-fill me-1"></i> 1. Personal & Contact Information
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label small fw-bold text-muted">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name') }}" required placeholder="e.g. Samuel Adebayo" autofocus>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label small fw-bold text-muted">Gmail Address <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="yourname@gmail.com">
                                <div class="form-text small">Must be a valid active Gmail address for account activation.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label small fw-bold text-muted">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="+1 (555) 000-0000">
                            </div>

                            <div class="col-md-6">
                                <label for="experience_years" class="form-label small fw-bold text-muted">Driving Experience</label>
                                <select id="experience_years" name="experience_years" class="form-select">
                                    <option value="1-2 years" {{ old('experience_years') == '1-2 years' ? 'selected' : '' }}>1 – 2 Years</option>
                                    <option value="3-5 years" {{ old('experience_years', '3-5 years') == '3-5 years' ? 'selected' : '' }}>3 – 5 Years</option>
                                    <option value="5+ years" {{ old('experience_years') == '5+ years' ? 'selected' : '' }}>5+ Years</option>
                                    <option value="10+ years" {{ old('experience_years') == '10+ years' ? 'selected' : '' }}>10+ Years (Professional)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Section 2: Driver's License & Vehicle Details -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-car-front-fill me-1"></i> 2. License & Vehicle Details
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="license_number" class="form-label small fw-bold text-muted">Driver's License Number <span class="text-danger">*</span></label>
                                <input type="text" id="license_number" name="license_number" class="form-control" value="{{ old('license_number') }}" required placeholder="e.g. DL-2026-98745">
                            </div>

                            <div class="col-md-6">
                                <label for="vehicle_type" class="form-label small fw-bold text-muted">Vehicle Type <span class="text-danger">*</span></label>
                                <select id="vehicle_type" name="vehicle_type" class="form-select" required>
                                    <option value="" disabled {{ old('vehicle_type') ? '' : 'selected' }}>Select Vehicle Type</option>
                                    <option value="Van" {{ old('vehicle_type') == 'Van' ? 'selected' : '' }}>Van (Passenger / Multi-Seat)</option>
                                    <option value="Minibus Shuttle" {{ old('vehicle_type') == 'Minibus Shuttle' ? 'selected' : '' }}>Minibus Shuttle</option>
                                    <option value="Electric Cart" {{ old('vehicle_type') == 'Electric Cart' ? 'selected' : '' }}>Electric Campus Cart</option>
                                    <option value="Sedan" {{ old('vehicle_type') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                                    <option value="SUV" {{ old('vehicle_type') == 'SUV' ? 'selected' : '' }}>SUV</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="vehicle_model" class="form-label small fw-bold text-muted">Vehicle Make & Model <span class="text-danger">*</span></label>
                                <input type="text" id="vehicle_model" name="vehicle_model" class="form-control" value="{{ old('vehicle_model') }}" required placeholder="e.g. Toyota HiAce 2023 (White)">
                            </div>

                            <div class="col-md-6">
                                <label for="plate_number" class="form-label small fw-bold text-muted">License Plate Number <span class="text-danger">*</span></label>
                                <input type="text" id="plate_number" name="plate_number" class="form-control" value="{{ old('plate_number') }}" required placeholder="e.g. CR-5542-AB">
                            </div>
                        </div>

                        <!-- Section 3: Notes & Additional Info -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-chat-text-fill me-1"></i> 3. Additional Details & Availability
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="notes" class="form-label small fw-bold text-muted">Availability & Comments</label>
                                <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Tell us your preferred driving schedule (e.g. Morning shuttles, Evening shifts, Full-time campus driver) or any relevant experience.">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-cr-primary py-2 fw-semibold fs-6">
                                <i class="bi bi-send-fill me-1"></i> Submit Driver Application
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="text-muted small mb-0">
                            Looking to register as a student passenger?
                            <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-none ms-1">
                                Student Registration &rarr;
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
