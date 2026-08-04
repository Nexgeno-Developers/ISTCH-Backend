<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center">
{{-- Illuminate\Mail\Markdown::parse($slot) --}}
<p>© {{ now()->year }} - {{ get_setting('name', config('app.name')) }}. All rights reserved.</p>
<p>
    <a href="{{ config('app.url') }}" style="color: #5d7594; text-decoration: none;">
        Visit our website
    </a>
</p>
</td>
</tr>
</table>
</td>
</tr>
