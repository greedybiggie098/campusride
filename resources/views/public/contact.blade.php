@extends('layouts.public')

@section('title', 'Contact & Help — CampusRide Mobility Services')

@section('content')
<!-- Page Header -->
<div class="py-5" style="background: linear-gradient(135deg, var(--cr-primary) 0%, var(--cr-primary-hover) 100%); color: #ffffff;">
    <div class="container py-3">
        <span class="badge bg-info text-dark fw-bold px-3 py-1 mb-2">24/7 Support</span>
        <h1 class="display-5 fw-bold mb-2">Contact Campus Dispatch</h1>
        <p class="lead text-light text-opacity-75 mb-0">
            Have questions, feedback, or need special transit accommodations? We are here to help.
        </p>
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container py-lg-4">
        <div class="row g-5">
            <!-- Contact Info & Dispatch Office -->
            <div class="col-lg-5">
                <div class="p-4 rounded-4 bg-light border h-100 shadow-sm">
                    <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Campus Operations Center</h4>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Physical Location</h6>
                            <p class="text-muted small mb-0">
                                Campus Center, Building B, Room 102<br>
                                University Transit & Mobility Services<br>
                                Main Campus Quadrangle
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="rounded-3 bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-telephone-inbound-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Dispatch Phone & Hotline</h6>
                            <p class="text-muted small mb-0">
                                <strong>Toll Free:</strong> (555) CAMPUS-RIDE<br>
                                <strong>Campus Extension:</strong> Ext. 4400<br>
                                <strong>Night Owl Hotline:</strong> (555) 234-NIGHT
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="rounded-3 bg-info text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Email Inquiries</h6>
                            <p class="text-muted small mb-0">
                                <strong>Dispatch Desk:</strong> dispatch@campusride.edu<br>
                                <strong>Student Services:</strong> mobility@campusride.edu
                            </p>
                        </div>
                    </div>

                    <div class="p-3 bg-white rounded-3 border">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check text-success me-1"></i>Emergency Safe Ride</h6>
                        <p class="small text-muted mb-0">
                            If you ever feel unsafe walking on campus at night, call our emergency hotline directly or request an urgent priority ride in the app.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white border">
                    <h3 class="fw-bold mb-2 text-dark">Send us a Message</h3>
                    <p class="text-muted small mb-4">Fill out the form below and our mobility coordinator will respond within 24 business hours.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm small mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label small fw-bold text-muted">Your Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', auth()->user()?->name) }}" required placeholder="e.g. Alex Morgan">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label small fw-bold text-muted">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', auth()->user()?->email) }}" required placeholder="e.g. student@campus.edu">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label small fw-bold text-muted">Phone Number</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', auth()->user()?->phone) }}" placeholder="+1 (555) 000-0000">
                            </div>
                            <div class="col-md-6">
                                <label for="subject" class="form-label small fw-bold text-muted">Inquiry Subject <span class="text-danger">*</span></label>
                                <select name="subject" id="subject" class="form-select" required>
                                    <option value="">Select a subject...</option>
                                    <option value="General Shuttle Inquiry" {{ old('subject') == 'General Shuttle Inquiry' ? 'selected' : '' }}>General Shuttle Inquiry</option>
                                    <option value="Accessibility Transit Request" {{ old('subject') == 'Accessibility Transit Request' ? 'selected' : '' }}>Accessibility Transit Request</option>
                                    <option value="Lost & Found Item" {{ old('subject') == 'Lost & Found Item' ? 'selected' : '' }}>Lost & Found Item</option>
                                    <option value="Driver Application / Work Study" {{ old('subject') == 'Driver Application / Work Study' ? 'selected' : '' }}>Driver Application / Work Study</option>
                                    <option value="Other Feedback" {{ old('subject') == 'Other Feedback' ? 'selected' : '' }}>Other Feedback</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label small fw-bold text-muted">Message Details <span class="text-danger">*</span></label>
                                <textarea name="message" id="message" rows="5" class="form-control" required placeholder="Describe your request, dates, locations, or feedback...">{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-cr-primary px-4 py-2 fw-semibold">
                                    <i class="bi bi-send-fill me-1"></i> Submit Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
