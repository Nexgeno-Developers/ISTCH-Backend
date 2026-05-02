<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <h1 class="h3 text-success mb-3">Thank you for your donation.</h1>
                        <p class="text-muted mb-4">Your payment is being confirmed. You will receive confirmation once Stripe completes processing.</p>
                        @if($payment)
                            <p class="fw-semibold">{{ $payment->currency }} {{ number_format((float) $payment->amount, 2) }}</p>
                        @endif
                        <a href="{{ route('donation.form') }}" class="btn btn-success">Return Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
