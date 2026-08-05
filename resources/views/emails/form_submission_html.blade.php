@extends('emails.layouts.base')

@section('content')
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:24px;font-weight:700;line-height:1.35;color:#2f4a6d;margin:0 0 24px;text-align:center;">
        {{ $emailTitle }}
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;border-collapse:collapse;table-layout:fixed;">
        @foreach ($rows as $row)
            <tr>
                <td style="width:32%;padding:14px 18px 14px 0;border-bottom:1px solid #edf3f8;vertical-align:top;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.6;color:#5f7898;font-weight:700;">
                    {{ $row['label'] }}
                </td>
                <td style="padding:14px 0;border-bottom:1px solid #edf3f8;vertical-align:top;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.6;color:#6b7f99;text-align:left;word-break:break-word;">
                    {!! nl2br(e($row['value'])) !!}
                </td>
            </tr>
        @endforeach
    </table>
@endsection
