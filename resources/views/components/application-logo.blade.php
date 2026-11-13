@php
    $logoUrl = app_logo_url();
    $appName = app_name('SevenKost');
@endphp
<img src="{{ $logoUrl }}" alt="{{ $appName }} Logo" {{ $attributes->merge(['class' => 'h-10 w-auto object-contain drop-shadow-sm']) }}>
