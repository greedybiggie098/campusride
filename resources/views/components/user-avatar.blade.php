@props(['user', 'size' => 32])

@if ($user->profile_picture)
    <img
        src="{{ asset('storage/'.$user->profile_picture) }}"
        alt="{{ $user->name }} profile picture"
        width="{{ $size }}"
        height="{{ $size }}"
        class="rounded-circle object-fit-cover"
        style="width: {{ $size }}px; height: {{ $size }}px;"
    >
@else
    <span
        class="rounded-circle bg-success text-white fw-bold d-inline-flex align-items-center justify-content-center"
        style="width: {{ $size }}px; height: {{ $size }}px; font-size: {{ max(0.7, $size / 40) }}rem;"
        aria-label="{{ $user->name }} profile picture placeholder"
    >
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </span>
@endif
