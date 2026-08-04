@props(['url'])

@php
    $brandName = get_setting('name', config('app.name'));
    $logoSrc = null;

    $companyLogoId = get_setting('logo');
    $companyLogo = $companyLogoId ? \App\Models\Upload::find($companyLogoId) : null;
    $localLogoPath = null;

    if ($companyLogo && blank($companyLogo->external_link) && filled($companyLogo->file_name)) {
        $candidatePath = public_path($companyLogo->file_name);
        if (is_file($candidatePath)) {
            $localLogoPath = $candidatePath;
        }
    }

    if ($localLogoPath && isset($message)) {
        $logoSrc = $message->embed($localLogoPath);
    } elseif ($companyLogo) {
        $logoSrc = filled($companyLogo->external_link)
            ? $companyLogo->external_link
            : central_asset($companyLogo->file_name);
    }

    if (! $logoSrc) {
        $defaultLogoPath = public_path('assets/backend/img/logo.png');

        if (is_file($defaultLogoPath) && isset($message)) {
            $logoSrc = $message->embed($defaultLogoPath);
        } else {
            $logoSrc = central_asset('assets/backend/img/logo.png');
        }
    }

    $hasLogo = filled($logoSrc);
@endphp

<tr>
<td class="header" style="padding: 0 0 24px; text-align: center;">
<a href="{{ $url }}" class="brand-link" style="display: inline-block; text-align: center;" target="_blank" rel="noopener">
@if ($hasLogo)
<img src="{{ $logoSrc }}" class="logo" alt="{{ $brandName }} logo" style="display: block; margin: 0 auto; border: 0; outline: none; text-decoration: none;">
@else
<span class="brand-name">{{ $brandName }}</span>
@endif
</a>
</td>
</tr>
