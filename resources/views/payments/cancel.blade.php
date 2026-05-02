<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Cancelled</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <h1 class="h3 mb-3">Payment cancelled</h1>
                        <p class="text-muted mb-4">No payment was completed. You can return to the donation form and try again.</p>
                        <a href="{{ route('donation.form') }}" class="btn btn-success">Try Again</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
