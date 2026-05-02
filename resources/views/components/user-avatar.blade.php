@props([
    'user' => null,
    'size' => 40,
    'subtitle' => null,
    'nameClass' => 'fw-semibold',
    'stacked' => false,
])

@php
    $photoUrl = $user?->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : null;
    $userInitial = $user?->name ? strtoupper(mb_substr($user->name, 0, 1)) : 'U';
    $avatarSize = (int) $size;
@endphp

<div
    {{ $attributes->merge(['class' => $stacked ? 'd-inline-flex flex-column align-items-start gap-1' : 'd-inline-flex align-items-center gap-2']) }}>
    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold overflow-hidden flex-shrink-0 border border-white"
        style="width: {{ $avatarSize }}px; height: {{ $avatarSize }}px; font-size: {{ max(12, (int) floor($avatarSize * 0.42)) }}px;">
        @if ($photoUrl)
            <img src="{{ $photoUrl }}" alt="Foto profil {{ $user?->name ?? 'user' }}" class="w-100 h-100"
                style="object-fit: cover;">
        @else
            {{ $userInitial }}
        @endif
    </div>

    <div class="lh-sm {{ $stacked ? 'text-start' : '' }}">
        <div class="{{ $nameClass }}">{{ $user?->name ?? '-' }}</div>
        @if (!is_null($subtitle))
            <div class="small text-muted">{{ $subtitle }}</div>
        @endif
    </div>
</div>
