@extends('layouts.public')

@section('title', 'About Us — CampusRide Mobility Services')

@section('content')
<!-- Page Header -->
<div class="py-5" style="background: linear-gradient(135deg, var(--cr-primary) 0%, var(--cr-dark) 100%); color: #ffffff;">
    <div class="container py-3">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-info text-dark fw-bold px-3 py-1 mb-2">About Our Mission</span>
                <h1 class="display-5 fw-bold mb-2">Connecting Campus Communities</h1>
                <p class="lead text-light text-opacity-75 mb-0">
                    CampusRide provides modern, student-centric, eco-friendly transit to make campus life safer, accessible, and connected.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Story & Core Values -->
<section class="py-5 bg-white">
    <div class="container py-lg-4">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 mb-2">Who We Are</span>
                <h2 class="fw-bold mb-3">Empowering Students with Safe & Affordable Transit</h2>
                <p class="text-muted">
                    Founded under the university transportation initiative, CampusRide was launched to address key student transit challenges: harsh weather walks across sprawling campus grounds, late-night safety concerns, and mobility barriers for students with special needs.
                </p>
                <p class="text-muted">
                    Today, our hybrid and electric campus shuttles cover every residence hall, faculty building, laboratory complex, athletic facility, and commuter transit point.
                </p>
                <div class="row g-3 mt-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-shield-lock-fill text-success fs-4"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Safety First</h6>
                                <p class="small text-muted mb-0">Verified university personnel and 24/7 dispatch tracking.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-tree-fill text-info fs-4"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Eco-Conscious</h6>
                                <p class="small text-muted mb-0">Reducing carbon footprint through shared electric shuttles.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 p-lg-5 rounded-4 shadow-sm" style="background: linear-gradient(135deg, var(--cr-light) 0%, #E9F5EE 100%); border: 1px solid #CDE5D6;">
                    <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-award-fill text-warning me-2"></i>Campus Standards</h4>
                    <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                        <li class="d-flex gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                <i class="bi bi-check"></i>
                            </div>
                            <div>
                                <strong>Certified Campus Drivers:</strong> Every driver undergoes rigorous background verification, student safety training, and vehicle certifications.
                            </div>
                        </li>
                        <li class="d-flex gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                <i class="bi bi-check"></i>
                            </div>
                            <div>
                                <strong>Subsidized Student Fares:</strong> Campus rides are capped at standard $2.50 student rates with campus card integration and emergency fare waivers.
                            </div>
                        </li>
                        <li class="d-flex gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                <i class="bi bi-check"></i>
                            </div>
                            <div>
                                <strong>Real-Time Dispatch Coordination:</strong> Admin monitors campus demand and re-allocates shuttle capacity during peak class transitions.
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership & Fleet Overview -->
<section class="py-5 bg-light">
    <div class="container py-lg-4">
        <div class="text-center mb-5">
            <span class="badge bg-secondary-subtle text-secondary fw-bold px-3 py-1 mb-2">Our Operations</span>
            <h2 class="fw-bold mb-2">Mobility Fleet & Dispatch Center</h2>
            <p class="text-muted">A dedicated team keeping campus moving rain or shine.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white h-100">
                    <div class="display-5 text-primary mb-3"><i class="bi bi-bus-front"></i></div>
                    <h5 class="fw-bold mb-2">High-Capacity Shuttles</h5>
                    <p class="text-muted small mb-0">
                        Ford Transit & Toyota HiAce passenger vans operating scheduled loops between main campus quad, commuter train terminal, and dorms.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white h-100">
                    <div class="display-5 text-success mb-3"><i class="bi bi-ev-front"></i></div>
                    <h5 class="fw-bold mb-2">Electric Quiet Shuttles</h5>
                    <p class="text-muted small mb-0">
                        Zero-emission electric carts operating in inner pedestrian plazas for quick access to libraries, laboratories, and lecture centers.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white h-100">
                    <div class="display-5 text-info mb-3"><i class="bi bi-headset"></i></div>
                    <h5 class="fw-bold mb-2">24/7 Dispatch Control</h5>
                    <p class="text-muted small mb-0">
                        Central operations desk monitoring on-duty driver status, ride assignments, special accessibility requests, and emergency transit.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
