@props([
    'tagline' => false,
    'size' => 44,
    'iconOnly' => false,
    'light' => false,
    'useImage' => true,
])

@php
    $textColor = $light ? '#FFFFFF' : '#064E3B';
    $accentColor = $light ? '#F4B400' : '#16A34A';
    $taglineColor = $light ? '#9CA3AF' : '#6B7280';
@endphp

<div style="display: inline-flex; align-items: center; gap: 0.75rem; text-decoration: none;" {{ $attributes->merge(['class' => 'campusride-brand-logo']) }}>
    @if($useImage && file_exists(public_path('images/campusride-logo.png')))
        <div class="campusride-logo-mark-wrap" style="width: {{ $size }}px; height: {{ $size }}px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(6, 78, 59, 0.18); flex-shrink: 0; background: #ffffff; display: flex; align-items: center; justify-content: center;">
            <img src="{{ asset('images/campusride-logo.png') }}" 
                 alt="CampusRide Logo" 
                 style="width: 100%; height: 100%; object-fit: cover; display: block;" />
        </div>
    @else
        <!-- Modern Premium Vector SVG Brand Mark -->
        <svg class="campusride-logo-mark" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink: 0; filter: drop-shadow(0 4px 10px rgba(6, 78, 59, 0.2));">
            <defs>
                <linearGradient id="crGrad1" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#064E3B"/>
                    <stop offset="1" stop-color="#047857"/>
                </linearGradient>
                <linearGradient id="crGradGold" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#F59E0B"/>
                    <stop offset="1" stop-color="#D97706"/>
                </linearGradient>
                <linearGradient id="crGradMint" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#34D399"/>
                    <stop offset="1" stop-color="#059669"/>
                </linearGradient>
            </defs>

            <!-- Rounded Crest Container -->
            <rect width="64" height="64" rx="16" fill="url(#crGrad1)" />

            <!-- Golden Road Arc Pathway -->
            <path d="M12 50 C24 40, 32 36, 48 30" stroke="url(#crGradGold)" stroke-width="4.5" stroke-linecap="round" />
            <path d="M16 54 C26 45, 34 41, 52 35" stroke="#FDE68A" stroke-width="2" stroke-linecap="round" opacity="0.6" />

            <!-- Graduation Cap Crest Motif -->
            <path d="M32 10 L50 18 L32 26 L14 18 Z" fill="#F59E0B" />
            <path d="M22 22.5 V29 C22 33 42 33 42 29 V22.5" stroke="#FEF3C7" stroke-width="2.5" fill="none" stroke-linecap="round" />
            <path d="M48 20 V28 L50 30" stroke="#F59E0B" stroke-width="2" fill="none" stroke-linecap="round" />

            <!-- Streamlined Campus Shuttle Vector -->
            <rect x="22" y="33" width="28" height="15" rx="5" fill="url(#crGradMint)" />
            <!-- Shuttle Windows -->
            <rect x="26" y="36" width="6" height="5" rx="1.5" fill="#E6FFFA" />
            <rect x="34" y="36" width="6" height="5" rx="1.5" fill="#E6FFFA" />
            <rect x="42" y="36" width="5" height="5" rx="1.5" fill="#E6FFFA" />
            <!-- Wheels -->
            <circle cx="28" cy="48" r="3" fill="#1F2937" />
            <circle cx="28" cy="48" r="1.2" fill="#E5E7EB" />
            <circle cx="43" cy="48" r="3" fill="#1F2937" />
            <circle cx="43" cy="48" r="1.2" fill="#E5E7EB" />
        </svg>
    @endif

    @unless($iconOnly)
        <span class="campusride-wordmark" style="display: inline-flex; flex-direction: column; line-height: 1.1;">
            <span class="campusride-name" style="color: {{ $textColor }}; font-size: 1.35rem; font-weight: 800; letter-spacing: -0.03em; display: flex; align-items: center; gap: 0.1rem;">
                <span>Campus</span><span style="color: {{ $accentColor }};">Ride</span>
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #F59E0B; margin-left: 2px;"></span>
            </span>
            @if($tagline)
                <span class="campusride-tagline" style="color: {{ $taglineColor }}; font-size: 0.55rem; font-weight: 800; letter-spacing: 0.22em; margin-top: 0.25rem; text-transform: uppercase;">
                    University Mobility
                </span>
            @endif
        </span>
    @endunless
</div>
