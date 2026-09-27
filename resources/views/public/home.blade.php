@extends('layouts.public')

@section('title', 'CampusRide — Safe & Reliable Campus Mobility')

@section('content')
<!-- Hero Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--cr-primary) 0%, var(--cr-primary-hover) 50%, var(--cr-dark) 100%); color: #ffffff;">
    <div class="container py-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-20 text-info small fw-semibold mb-3">
                    <i class="bi bi-patch-check-fill text-info"></i>
                    <span>Official University Student Transit System</span>
                </div>
                <h1 class="display-4 fw-extrabold text-white mb-3" style="line-height: 1.15; font-weight: 800;">
                    Smarter, Safer & Faster <br><span style="color: var(--cr-accent);">Campus Rides</span> Every Day.
                </h1>
                <p class="lead text-light text-opacity-75 mb-4" style="font-size: 1.15rem;">
                    Skip the long walks in bad weather and get to your lectures, labs, and dorms on time. CampusRide connects students and faculty with verified campus shuttles and drivers.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    @auth
                        @php
                            $dash = match (auth()->user()->role) {
                                'admin'  => route('admin.dashboard'),
                                'driver' => route('driver.dashboard'),
                                default  => route('passenger.dashboard'),
                            };
                        @endphp
                        <a href="{{ $dash }}" class="btn btn-cr-accent btn-lg px-4 d-flex align-items-center gap-2">
                            <i class="bi bi-speedometer2"></i>
                            <span>Go to My Dashboard</span>
                        </a>
                        @if(auth()->user()->isPassenger())
                            <a href="{{ route('passenger.rides.create') }}" class="btn btn-outline-light btn-lg px-4">
                                <i class="bi bi-plus-circle me-1"></i> Book Shuttle
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn btn-cr-accent btn-lg px-4 d-flex align-items-center gap-2">
                            <i class="bi bi-car-front-fill"></i>
                            <span>Book a Ride (Register)</span>
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                        </a>
                    @endauth
                </div>
                <div class="d-flex align-items-center gap-4 text-white-50 small">
                    <div><i class="bi bi-shield-check text-success me-1"></i> University Drivers Only</div>
                    <div><i class="bi bi-clock-history text-info me-1"></i> Under 5-min Avg Wait</div>
                    <div><i class="bi bi-credit-card-2-front text-warning me-1"></i> Campus Card Ready</div>
                </div>
            </div>

            <!-- Quick Booking / Fare Estimator Card -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden text-dark bg-white">
                    <div class="card-header bg-dark text-white p-4 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-info text-dark fw-bold mb-1">Live Shuttle Dispatch</span>
                                <h5 class="fw-bold mb-0">Estimate & Book Ride</h5>
                            </div>
                            <div class="text-end">
                                <span class="fs-4 text-warning fw-bold">${{ number_format((float) $baseFare, 2) }}</span>
                                <div class="text-white-50 small">Flat Campus Fare</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i> Pickup Location
                            </label>
                            <select class="form-select bg-light" id="quickPickup">
                                <option>North Residence Halls</option>
                                <option>Main Library Quad</option>
                                <option>Student Union Center</option>
                                <option>Science & Lab Complex</option>
                                <option>South Sports Pavilion</option>
                                <option>Metro Transit Plaza Gate</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1">
                                <i class="bi bi-pin-map-fill text-primary me-1"></i> Destination
                            </label>
                            <select class="form-select bg-light" id="quickDropoff">
                                <option selected>Main Library Quad</option>
                                <option>Student Union Center</option>
                                <option>Science & Lab Complex</option>
                                <option>South Sports Pavilion</option>
                                <option>North Residence Halls</option>
                                <option>Metro Transit Plaza Gate</option>
                            </select>
                        </div>
                        <div class="p-3 bg-light rounded-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted">Estimated Wait:</span>
                                <span class="small fw-bold text-success"><i class="bi bi-dot"></i> 3-5 mins</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted">Payment:</span>
                                <span class="small fw-semibold">Campus Card / Student Wallet / Cash</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">Active Shuttles:</span>
                                <span class="small fw-bold text-primary">{{ max($stats['active_drivers'], 2) }} available nearby</span>
                            </div>
                        </div>

                        @auth
                            <a href="{{ route('passenger.rides.create') }}" class="btn btn-cr-primary w-100 py-2 fs-6">
                                Proceed to Book Ride <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-cr-primary w-100 py-2 fs-6">
                                Register as Student to Book <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Live Stats Section -->
<section class="py-4 bg-white border-bottom">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="p-3">
                    <div class="display-6 fw-bold text-primary mb-1">{{ max($stats['active_drivers'], 2) }}</div>
                    <div class="text-muted small fw-semibold text-uppercase">Active Shuttles On Duty</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3">
                    <div class="display-6 fw-bold text-info mb-1">< 4 min</div>
                    <div class="text-muted small fw-semibold text-uppercase">Average Response Time</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3">
                    <div class="display-6 fw-bold text-success mb-1">{{ max($stats['completed_rides'], 48) }}+</div>
                    <div class="text-muted small fw-semibold text-uppercase">Completed Safe Trips</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3">
                    <div class="display-6 fw-bold text-warning mb-1">100%</div>
                    <div class="text-muted small fw-semibold text-uppercase">Vetted Campus Drivers</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-light">
    <div class="container py-lg-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 mb-2">Simple 3-Step Process</span>
            <h2 class="fw-bold text-dark mb-2">How CampusRide Works</h2>
            <p class="text-muted">Designed specifically for university students and faculty for seamless campus travel.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 campus-card-hover bg-white text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.75rem;">
                        <i class="bi bi-phone-fill"></i>
                    </div>
                    <span class="badge bg-light text-muted border mx-auto mb-2">Step 1</span>
                    <h5 class="fw-bold mb-2">Request Your Ride</h5>
                    <p class="text-muted small mb-0">
                        Choose your campus pickup spot, lecture hall or residence, and select your destination with estimated fare preview.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 campus-card-hover bg-white text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-info-subtle text-info rounded-circle mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.75rem;">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                    <span class="badge bg-light text-muted border mx-auto mb-2">Step 2</span>
                    <h5 class="fw-bold mb-2">Driver Match & Acceptance</h5>
                    <p class="text-muted small mb-0">
                        Campus dispatch pairs you with an on-duty shuttle. View driver name, vehicle model, plate number, and arrival status.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 campus-card-hover bg-white text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.75rem;">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                    <span class="badge bg-light text-muted border mx-auto mb-2">Step 3</span>
                    <h5 class="fw-bold mb-2">Arrive Safely & Pay</h5>
                    <p class="text-muted small mb-0">
                        Enjoy your ride across campus. Tap your Campus Card, use student wallet or cash upon arrival, and check your digital receipt.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Campus Hubs Section -->
<section class="py-5 bg-white">
    <div class="container py-lg-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
            <div>
                <span class="badge bg-info-subtle text-info fw-bold px-3 py-1 mb-2">Campus Coverage</span>
                <h2 class="fw-bold text-dark mb-1">Key Campus Shuttle Hubs</h2>
                <p class="text-muted mb-0">Direct routes and pickup zones across the entire campus perimeter.</p>
            </div>
            <a href="{{ route('services') }}" class="btn btn-outline-primary btn-sm fw-semibold">
                View Full Route Schedule <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            @foreach($popularHubs as $hub)
                <div class="col-md-4 col-sm-6">
                    <div class="card border rounded-3 p-3 h-100 campus-card-hover bg-light bg-opacity-50">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-white text-primary shadow-sm border" style="width: 48px; height: 48px; font-size: 1.25rem;">
                                <i class="bi {{ $hub['icon'] }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-dark mb-0">{{ $hub['name'] }}</h6>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="badge bg-secondary-subtle text-secondary small">{{ $hub['category'] }}</span>
                                    <span class="text-success small fw-semibold"><i class="bi bi-clock me-1"></i>{{ $hub['shuttle_mins'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-light">
    <div class="container py-lg-4">
        <div class="text-center mb-5">
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 mb-2">Campus Voices</span>
            <h2 class="fw-bold text-dark mb-2">What Students & Drivers Say</h2>
            <p class="text-muted">Over 1,200+ campus trips facilitated every month.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <div class="text-warning mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="text-muted small mb-3">
                        "CampusRide is a lifesaver during rainy evenings. When I have lab sessions that finish at 9 PM, I know I have a reliable safe ride back to North Residence."
                    </p>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">JP</div>
                        <div>
                            <div class="fw-bold small">Jane Passenger</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Computer Science, 3rd Year</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <div class="text-warning mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="text-muted small mb-3">
                        "The driver dashboard is so easy to use. I can toggle my availability with one click and see my assigned pickups without any confusion."
                    </p>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-info text-white fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">AD</div>
                        <div>
                            <div class="fw-bold small">Alex Driver</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Toyota HiAce Campus Shuttle Driver</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <div class="text-warning mb-2">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <p class="text-muted small mb-3">
                        "Fast, cheap, and very convenient. Paying with my campus card balance makes booking rides between classes completely friction-free."
                    </p>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">MC</div>
                        <div>
                            <div class="fw-bold small">Michael Chen</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Mechanical Engineering, 2nd Year</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--cr-primary) 0%, var(--cr-secondary) 100%); color: #ffffff;">
    <div class="container text-center py-lg-4">
        <h2 class="display-6 fw-bold mb-3">Ready to Experience Campus Mobility?</h2>
        <p class="lead text-light mb-4 max-w-600 mx-auto" style="font-size: 1.1rem;">
            Join your fellow students and staff today. Request your first ride or join as a student shuttle driver.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            @auth
                <a href="{{ $dash }}" class="btn btn-light btn-lg px-4 text-primary fw-bold">
                    Open Your Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 text-primary fw-bold">
                    <i class="bi bi-person-plus-fill me-1"></i> Student Register
                </a>
                <a href="{{ route('driver.apply') }}" class="btn btn-warning btn-lg px-4 text-dark fw-bold">
                    <i class="bi bi-car-front-fill me-1"></i> Apply to Drive
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                    Sign In
                </a>
            @endauth
        </div>
    </div>
</section>
@endsection
