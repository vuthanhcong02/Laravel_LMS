@props([
    'user' => null,
    'size' => 'size-9',
    'border' => 'border-2 border-primary',
    'class' => '',
])

@php
    $targetUser = $user ?? auth()->user();
    $avatarUrl = $targetUser?->avatar_url;
    $firstName = $targetUser?->first_name ?? 'U';
    $lastName = $targetUser?->last_name ?? '';
    $initials = strtoupper(substr($firstName, 0, 1) . ($lastName ? substr($lastName, 0, 1) : ''));
    $hasCustomAvatar = !empty($targetUser?->avatar);
@endphp

<div class="relative shrink-0 {{ $size }} {{ $class }}">
    @if ($hasCustomAvatar && $avatarUrl)
        <img src="{{ $avatarUrl }}" 
            alt="{{ $firstName }}"
            referrerpolicy="no-referrer"
            class="{{ $size }} rounded-full {{ $border }} object-cover"
            onerror="this.style.display='none'; if(this.nextElementSibling){ this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex'); }">
        <div class="hidden {{ $size }} rounded-full {{ $border }} bg-primary items-center justify-center text-white text-xs font-bold select-none">
            {{ $initials }}
        </div>
    @else
        <div class="flex {{ $size }} rounded-full {{ $border }} bg-primary items-center justify-center text-white text-xs font-bold select-none">
            {{ $initials }}
        </div>
    @endif
</div>
