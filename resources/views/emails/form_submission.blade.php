{{-- resources/views/emails/form_submission.blade.php --}}

@component('mail::message')
# {{ ucfirst(str_replace('_', ' ', $formName)) }} Form Submission

@foreach($data as $key => $value)
**{{ ucwords(str_replace('_', ' ', $key)) }}:** @if(is_array($value)){{ implode(', ', $value) }}@elseif(is_bool($value)){{ $value ? 'Yes' : 'No' }}@else{{ $value ?? 'N/A' }}@endif

@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent
