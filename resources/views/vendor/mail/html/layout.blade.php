<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
@media only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}
</style>
{{ $head ?? '' }}
</head>
<body style="margin: 0; padding: 0; background-color: #e8eff5;">

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="width: 100%; margin: 0; padding: 0; background-color: #e8eff5;">
<tr>
<td class="top-stripe" style="height: 18px; line-height: 18px; font-size: 0; background-color: #1d7faa;">&nbsp;</td>
</tr>
<tr>
<td align="center" style="background-color: #e8eff5; padding: 24px 12px 32px;">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="width: 100%; background-color: #e8eff5;">
{{ $header ?? '' }}

<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0" style="width: 100%; background-color: #e8eff5; border: 0; padding: 0 0 20px;">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="width: 570px; max-width: 570px; margin: 0 auto; background-color: #ffffff; border: 1px solid #dfe7f0; border-radius: 18px;">
<!-- Body content -->
<tr>
<td class="content-cell">
{{ Illuminate\Mail\Markdown::parse($slot) }}

{{ $subcopy ?? '' }}
</td>
</tr>
</table>
</td>
</tr>

{{ $footer ?? '' }}
</table>
</td>
</tr>
</table>
</body>
</html>
