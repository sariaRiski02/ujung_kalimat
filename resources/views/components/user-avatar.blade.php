@props(['user', 'size' => 'w-10 h-10', 'text' => 'text-sm'])

@if ($user->profile_photo_url)
    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" {{ $attributes->merge(['class' => "$size rounded-full object-cover shrink-0"]) }}>
@else
    <span {{ $attributes->merge(['class' => "$size $text rounded-full shrink-0 bg-gray-100 border border-gray-200 flex items-center justify-center font-semibold text-gray-500"]) }}>{{ $user->initials() }}</span>
@endif
