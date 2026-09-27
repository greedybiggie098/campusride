@extends('layouts.public')

@section('title', 'Mobility Services — CampusRide')

@section('content')
<!-- Page Header -->
<div class="py-5" style="background: linear-gradient(135deg, var(--cr-primary) 0%, var(--cr-primary-hover) 100%); color: #ffffff;">
    <div class="container py-3">
        <span class="badge bg-info text-dark fw-bold px-3 py-1 mb-2">Transit Programs</span>
        <h1 class="display-5 fw-bold mb-2">Campus Mobility Services</h1>
        <p class="lead text-light text-opacity-75 mb-0">
            Comprehensive on-demand and fixed-route transit options designed for modern university life.
        </p>
    </div>
</div>

<!-- Services Grid -->
<section class="py-5 bg-white">
    <div class="container py-lg-4">
        <div class="row g-4 mb-5">
            <!-- Service 1 -->
            <div class="col-lg-6">
                <div class="card border rounded-4 p-4 h-100 shadow-sm campus-card-hover bg-light bg-opacity-25">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Daytime Campus Loops</h4>
                            <span class="badge bg-primary-subtle text-primary">6:30 AM – 8:00 PM</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">
                        Continuous campus shuttle loops connecting main lecture halls, libraries, dining commons, and perimeter parking lots every 10 minutes.
                    </p>
                    <div class="bg-white p-3 rounded-3 border">
                        <div class="row g-2 text-dark small">
                            <div class="col-6"><strong>Standard Fare:</strong> $2.50</div>
                            <div class="col-6"><strong>Payment:</strong> Campus Card / Wallet</div>
                            <div class="col-6"><strong>Booking:</strong> On-Demand / App</div>
                            <div class="col-6"><strong>Fleet:</strong> 15-Passenger Vans</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service 2 -->
            <div class="col-lg-6">
                <div class="card border rounded-4 p-4 h-100 shadow-sm campus-card-hover bg-light bg-opacity-25">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-dark text-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="bi bi-moon-stars-fill"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Night Owl Safe Ride</h4>
                            <span class="badge bg-warning-subtle text-dark">8:00 PM – 3:30 AM</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">
                        Direct point-to-point escort shuttle service for students studying late in labs, libraries, or arriving late from commuter transit.
                    </p>
                    <div class="bg-white p-3 rounded-3 border">
                        <div class="row g-2 text-dark small">
                            <div class="col-6"><strong>Standard Fare:</strong> $2.50 (Free w/ ID)</div>
                            <div class="col-6"><strong>Priority:</strong> Late-Night Safe Escort</div>
                            <div class="col-6"><strong>Dropoff:</strong> Door-to-Door Residence</div>
                            <div class="col-6"><strong>Dispatch:</strong> Real-Time GPS Tracking</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service 3 -->
            <div class="col-lg-6">
                <div class="card border rounded-4 p-4 h-100 shadow-sm campus-card-hover bg-light bg-opacity-25">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-info text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="bi bi-universal-access"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Accessible Transit</h4>
                            <span class="badge bg-info-subtle text-info">Full Campus Hours</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">
                        Specialized wheel-chair accessible shuttles with hydraulic ramps and trained drivers for students with mobility needs and temporary injuries.
                    </p>
                    <div class="bg-white p-3 rounded-3 border">
                        <div class="row g-2 text-dark small">
                            <div class="col-6"><strong>Rate:</strong> University Subsidized</div>
                            <div class="col-6"><strong>Vehicle:</strong> Ramp-Equipped Shuttle</div>
                            <div class="col-6"><strong>Scheduling:</strong> On-Demand / Scheduled</div>
                            <div class="col-6"><strong>Assistance:</strong> Doorstep Driver Help</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service 4 -->
            <div class="col-lg-6">
                <div class="card border rounded-4 p-4 h-100 shadow-sm campus-card-hover bg-light bg-opacity-25">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-success text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Event & Group Shuttles</h4>
                            <span class="badge bg-success-subtle text-success">Upon Request</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">
                        Charter transit for student organizations, sports teams, academic conferences, campus open days, and department field events.
                    </p>
                    <div class="bg-white p-3 rounded-3 border">
                        <div class="row g-2 text-dark small">
                            <div class="col-6"><strong>Capacity:</strong> Up to 30 Passengers</div>
                            <div class="col-6"><strong>Booking:</strong> 48h Advance Notice</div>
                            <div class="col-6"><strong>Routes:</strong> Customized Campus Path</div>
                            <div class="col-6"><strong>Contact:</strong> Dispatch Office</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shuttle Route Reference Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-dark text-white py-3 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-map-fill text-info me-2"></i>Campus Route Schedules & Timing</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Route Name</th>
                            <th>Key Stops</th>
                            <th>Frequency</th>
                            <th>Operating Days</th>
                            <th>Fare</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold text-primary"><i class="bi bi-circle-fill text-primary small me-2"></i>Blue Line (North-South)</td>
                            <td>North Residence → Main Library → Science Lab → Sports Pavilion</td>
                            <td>Every 8 mins</td>
                            <td>Mon – Fri</td>
                            <td>$2.50</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-success"><i class="bi bi-circle-fill text-success small me-2"></i>Green Line (Commuter Hub)</td>
                            <td>Metro Transit Plaza → Engineering Complex → Student Union</td>
                            <td>Every 10 mins</td>
                            <td>Mon – Sat</td>
                            <td>$2.50</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-warning"><i class="bi bi-circle-fill text-warning small me-2"></i>Night Owl Express</td>
                            <td>All Campus Dorms & Libraries (Direct Door-to-Door)</td>
                            <td>On-Demand (< 5 min)</td>
                            <td>Every Night</td>
                            <td>$2.50</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="max-w-800 mx-auto">
            <h3 class="fw-bold text-center mb-4">Frequently Asked Questions</h3>
            <div class="accordion shadow-sm rounded-4 overflow-hidden" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            How do I request a ride?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Simply log in to your student account, go to "Request Ride", choose your pickup hub and drop-off point, and click "Submit Request". An on-duty driver will be assigned within minutes.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            What payment methods are supported?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            You can pay with your Campus ID Card, Student Transit Wallet, or Cash directly to the driver upon completion. Fares are clearly shown before booking.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Can I request a ride late at night?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Yes! Our Night Owl Safe Ride operates every night until 3:30 AM to ensure that students arriving late or leaving study halls reach their dormitories safely.
                        </div>
                    </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            How do I become a CampusRide driver?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Interested campus drivers can apply online through our <a href="{{ route('driver.apply') }}" class="fw-bold text-success text-decoration-none">Driver Application Form</a> with their driver's license and vehicle specifications. After operations review, approved drivers receive their account credentials via Gmail.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Driver Recruitment Banner -->
<div class="container pb-5">
    <div class="card border-0 rounded-4 p-4 p-md-5 text-white shadow-lg overflow-hidden position-relative" style="background: linear-gradient(135deg, var(--cr-primary) 0%, #0d503f 100%);">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark fw-bold px-3 py-1 mb-2">Driver Opportunities</span>
                <h3 class="fw-bold mb-2">Want to Drive for CampusRide?</h3>
                <p class="text-white-50 mb-0">
                    We are looking for dedicated drivers for daytime campus shuttles and late-night student safety escorts. Flexible schedules, verified riders, and competitive campus rates.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('driver.apply') }}" class="btn btn-warning btn-lg px-4 fw-bold text-dark">
                    <i class="bi bi-car-front-fill me-1"></i> Apply to Drive
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
