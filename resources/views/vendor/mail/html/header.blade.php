@props(['url'])

@php
    $brandName = get_setting('name', config('app.name'));
    $logoUrl = central_asset(backend_logo_url());
    $hasLogo = filled($logoUrl);
@endphp

<tr>
<td class="header">
<a href="{{ $url }}" class="brand-link" style="display: inline-block;" target="_blank" rel="noopener">
@if ($hasLogo)
<img src="{{ $logoUrl }}" class="logo" alt="{{ $brandName }} logo">
@else
<span class="brand-name">{{ $brandName }}</span>
@endif
</a>
</td>
</tr>
