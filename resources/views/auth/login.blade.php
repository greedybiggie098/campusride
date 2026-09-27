@extends('layouts.public')

@section('title', 'Sign In — CampusRide')

@section('styles')
<style>
    .login-page {
        background: linear-gradient(135deg, #f3f7f4 0%, #edf4ef 100%);
        min-height: calc(100vh - 76px);
        padding: 2rem 0;
    }

    .auth-shell {
        background: #ffffff;
        border: 1px solid rgba(6, 78, 59, 0.08);
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(6, 78, 59, 0.10);
    }

    .auth-visual {
        background: linear-gradient(160deg, #064E3B 0%, #0d5f42 40%, #16A34A 100%);
        position: relative;
        min-height: 760px;
        padding: 2.5rem 2rem;
        color: #fff;
    }

    .auth-visual::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(244, 180, 0, 0.25), transparent 27%),
                    linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0));
    }

    .auth-visual > * {
        position: relative;
        z-index: 1;
    }

    .auth-panel {
        background: #ffffff;
        padding: 2.5rem 2.2rem;
    }

    .auth-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.85rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .auth-visual-card {
        margin-top: 2rem;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 24px;
        padding: 1.25rem;
        backdrop-filter: blur(8px);
    }

    .auth-illustration {
        width: 100%;
        max-width: 340px;
        margin: 2rem auto 1.25rem;
        display: block;
        filter: drop-shadow(0 26px 35px rgba(9, 39, 31, 0.22));
    }

    .login-title { color: #17211D; letter-spacing: -0.04em; }
    .login-subtitle { color: #5B6B63; font-size: 1rem; }

    .login-card .input-group-text,
    .login-card .form-control {
        min-height: 3.45rem;
        border-color: #E3E8E6;
    }

    .login-card .input-group-text {
        width: 3.45rem;
        justify-content: center;
        background: #F7F9F8 !important;
    }

    .login-card .form-control {
        background: #fff;
    }

    .login-card .form-control:focus {
        border-color: rgba(6, 78, 59, 0.4);
        box-shadow: 0 0 0 0.2rem rgba(22, 163, 74, 0.12);
    }

    .login-card .form-check-input:checked {
        background-color: #064E3B;
        border-color: #064E3B;
    }

    .login-submit {
        background: linear-gradient(135deg, #064E3B 0%, #16A34A 100%);
        border: 0;
        min-height: 3.2rem;
        font-size: 1rem;
        border-radius: 0.9rem;
    }

    .login-submit:hover { background: #022C22; }

    @media (max-width: 991.98px) {
        .auth-visual {
            min-height: 280px;
        }
    }
</style>
@endsection

@section('content')
<div class="py-5 login-page flex-grow-1 d-flex align-items-center">
    <div class="container py-3">
        <div class="auth-shell row g-0 mx-auto" style="max-width: 1100px;">
            <div class="col-lg-5 auth-visual d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <x-campusride-logo :size="50" light tagline />
                    </div>
                    <div class="auth-badge"><i class="bi bi-shield-check"></i> Official University Transit</div>
                </div>

                <div class="text-center my-auto py-3">
                    <div class="auth-visual-card mx-auto mb-4">
                        <img src="{{ asset('images/campusride-logo.png') }}" 
                             alt="CampusRide Crest Logo" 
                             class="rounded-4 shadow-lg mx-auto d-block" 
                             style="width: 140px; height: 140px; object-fit: cover; border: 3px solid rgba(255, 255, 255, 0.25);">
                        <h4 class="fw-bold mt-3 mb-1 text-white">Safe Rides • Better Campus Life</h4>
                        <p class="mb-0 small text-white-50">Reliable campus transport for students, drivers, and faculty.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 auth-panel d-flex align-items-center">
                <div class="w-100 login-card">
                    <div class="text-center mb-4">
                        <h1 class="login-title fw-bold mb-2" style="font-size: clamp(2.2rem, 3vw, 4rem);">Welcome Back</h1>
                        <p class="login-subtitle mb-0">Sign in to your CampusRide portal account</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success small border-0 shadow-sm mb-3">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger small border-0 shadow-sm mb-3">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold text-muted">Email Address</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                value=""
                                placeholder="Enter your Gmail address"
                                autocomplete="off"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label small fw-bold text-muted mb-0">Password</label>
                                @if (\Illuminate\Support\Facades\Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none text-muted">Forgot your password?</a>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" id="password" name="password" class="form-control border-start-0" required autocomplete="current-password" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small text-muted" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn login-submit btn-cr-primary w-100 py-2 fw-semibold mb-3">
                            Sign In to Portal <i class="bi bi-box-arrow-in-right ms-1"></i>
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top">
                        <span class="text-muted small">Don't have an account yet?</span>
                        <a href="{{ route('register') }}" class="fw-bold text-primary small text-decoration-none ms-1">Create Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
