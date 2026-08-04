{{-- resources/views/emails/form_submission.blade.php --}}

@component('mail::message')
<h1 class="mail-card-title">{{ ucfirst(str_replace('_', ' ', $formName)) }} Form Submission</h1>

<table class="mail-field-table" role="presentation" width="100%" cellpadding="0" cellspacing="0">
@foreach($data as $key => $value)
    @php
        $displayValue = match (true) {
            is_array($value) => implode(', ', array_map(fn ($item) => is_scalar($item) ? (string) $item : json_encode($item), $value)),
            is_bool($value) => $value ? 'Yes' : 'No',
            filled($value) => (string) $value,
            default => 'N/A',
        };
    @endphp
<tr class="mail-field-row">
    <td class="mail-field-label">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
    <td class="mail-field-value">{!! nl2br(e($displayValue)) !!}</td>
</tr>
@endforeach
</table>

<p class="mail-signoff">
Thanks,<br>
{{ get_setting('name', config('app.name')) }}
</p>
@endcomponent
