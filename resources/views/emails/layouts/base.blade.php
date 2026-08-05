<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brandName }}</title>
</head>
<body style="margin:0;padding:0;background-color:#fdf3e6;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;margin:0;padding:0;background-color:#fdf3e6;border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:28px 12px 36px;background-color:#fdf3e6;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:620px;border-collapse:collapse;">
                    <tr>
                        <td align="center" style="padding:0 0 26px;">
                            <a href="{{ $brandWebsite }}" target="_blank" rel="noopener" style="text-decoration:none;display:inline-block;">
                                @if (!empty($logoPath) && isset($message))
                                    <img src="{{ $message->embed($logoPath) }}" alt="{{ $brandName }} logo" style="display:block;max-width:220px;max-height:78px;width:auto;height:auto;border:0;outline:none;text-decoration:none;">
                                @elseif (!empty($logoUrl))
                                    <img src="{{ $logoUrl }}" alt="{{ $brandName }} logo" style="display:block;max-width:220px;max-height:78px;width:auto;height:auto;border:0;outline:none;text-decoration:none;">
                                @else
                                    <span style="display:inline-block;font-family:Arial,Helvetica,sans-serif;font-size:30px;font-weight:700;line-height:1.2;color:#2f4a6d;letter-spacing:0.08em;text-transform:uppercase;">{{ $brandName }}</span>
                                @endif
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#ffffff;border:1px solid #dfe7f0;border-radius:18px;padding:0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;border-collapse:collapse;">
                                <tr>
                                    <td style="padding:38px 38px 34px;font-family:Arial,Helvetica,sans-serif;color:#6b7f99;">
                                        @yield('content')
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:24px 20px 0;font-family:Arial,Helvetica,sans-serif;color:#7c8ea5;font-size:12px;line-height:1.7;text-align:center;">
                            <div>&copy; {{ now()->year }} - {{ $brandName }}. All rights reserved.</div>
                            <div style="padding-top:6px;">
                                <a href="{{ $brandWebsite }}" target="_blank" rel="noopener" style="color:#5d7594;text-decoration:none;">Visit our website</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
