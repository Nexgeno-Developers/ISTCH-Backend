<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secure Donation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --donation-blue: #08a7df;
            --donation-blue-dark: #0298cf;
            --donation-orange: #f58200;
            --donation-ink: #191b2f;
            --donation-muted: #72808f;
            --donation-border: #e9eef5;
            --donation-field: #f8fafc;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            color: var(--donation-ink);
            background: var(--donation-blue);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .page-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 24px;
            background:
                radial-gradient(circle at 18% 82%, rgba(255, 255, 255, .18), transparent 24%),
                linear-gradient(135deg, var(--donation-blue), var(--donation-blue-dark));
        }

        .donation-layout {
            width: min(1180px, 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 430px;
            align-items: center;
            gap: clamp(28px, 6vw, 90px);
        }

        .movement-copy { color: #fff; max-width: 560px; }
        .movement-copy h1 {
            margin: 0 0 18px;
            font-size: clamp(34px, 4vw, 54px);
            line-height: 1.05;
            font-weight: 800;
            letter-spacing: 0;
        }
        .movement-copy p {
            max-width: 500px;
            margin: 0;
            font-size: 15px;
            line-height: 1.75;
            color: rgba(255, 255, 255, .9);
        }
        .donor-proof {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 34px;
        }
        .avatar-stack { display: flex; }
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid #fff;
            margin-left: -9px;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 13px;
            color: #fff;
            background: #0f766e;
        }
        .avatar:first-child { margin-left: 0; background: #f59e0b; }
        .avatar:nth-child(2) { background: #64748b; }
        .avatar:nth-child(3) { background: #0284c7; }
        .donor-proof span {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .donation-card {
            background: #fff;
            border-radius: 10px;
            padding: 34px;
            box-shadow: 0 28px 70px rgba(15, 23, 42, .18);
        }
        .card-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
        }
        .card-header-row h2 {
            margin: 0;
            font-size: 22px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: 0;
        }
        .secure-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--donation-blue);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .secure-badge svg { width: 15px; height: 15px; }

        .type-toggle {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            padding: 4px;
            border-radius: 7px;
            background: #eceeef;
            margin-bottom: 26px;
        }
        .type-toggle input { position: absolute; opacity: 0; pointer-events: none; }
        .type-toggle label {
            margin: 0;
            min-height: 44px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #71808f;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: background .18s ease, color .18s ease, box-shadow .18s ease;
        }
        .type-toggle input:checked + label {
            background: #fff;
            color: var(--donation-blue);
            box-shadow: 0 1px 5px rgba(15, 23, 42, .08);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .donation-control {
            width: 100%;
            height: 47px;
            border: 1px solid transparent;
            border-radius: 5px;
            background-color: var(--donation-field);
            color: var(--donation-ink);
            font-size: 13px;
            font-weight: 700;
            padding: 0 16px;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        }
        .donation-control::placeholder { color: #c9d3df; opacity: 1; }
        .donation-control:focus {
            background: #fff;
            border-color: var(--donation-blue);
            box-shadow: 0 0 0 3px rgba(8, 167, 223, .12);
        }
        select.donation-control {
            appearance: none;
            background-image:
                linear-gradient(45deg, transparent 50%, #c9d3df 50%),
                linear-gradient(135deg, #c9d3df 50%, transparent 50%);
            background-position:
                calc(100% - 18px) 19px,
                calc(100% - 12px) 19px;
            background-size: 6px 6px, 6px 6px;
            background-repeat: no-repeat;
        }
        .amount-input-wrap { position: relative; }
        .amount-input-wrap .amount-symbol {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #72808f;
            font-size: 17px;
            font-weight: 900;
            pointer-events: none;
        }
        .amount-input-wrap input { padding-left: 40px; }

        .amount-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            margin-top: 18px;
            border: 1px solid var(--donation-border);
            border-radius: 6px;
            overflow: hidden;
        }
        .amount-btn {
            min-height: 48px;
            border: 0;
            border-right: 1px solid var(--donation-border);
            border-bottom: 1px solid var(--donation-border);
            background: #fff;
            color: var(--donation-ink);
            font-size: 13px;
            font-weight: 900;
            transition: background .18s ease, color .18s ease, box-shadow .18s ease;
        }
        .amount-btn:nth-child(3n) { border-right: 0; }
        .amount-btn:nth-last-child(-n+3) { border-bottom: 0; }
        .amount-btn.active {
            position: relative;
            z-index: 1;
            background: #eefaff;
            color: var(--donation-blue);
            box-shadow: inset 0 0 0 2px var(--donation-blue);
        }

        .donate-button {
            width: 100%;
            min-height: 56px;
            border: 0;
            border-radius: 6px;
            background: var(--donation-orange);
            color: #fff;
            margin-top: 18px;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: .02em;
            text-transform: uppercase;
            box-shadow: 0 20px 32px rgba(245, 130, 0, .24);
            transition: transform .18s ease, background .18s ease;
        }
        .donate-button:hover { background: #e87500; transform: translateY(-1px); }
        .donate-button:disabled { opacity: .7; transform: none; }
        .heart { margin-left: 10px; font-size: 22px; line-height: 0; vertical-align: -2px; }
        .tax-note {
            margin: 16px 0 0;
            color: #7a8795;
            font-size: 11px;
            text-align: center;
        }

        .alert { font-size: 13px; }

        @media (max-width: 991.98px) {
            .page-shell { align-items: flex-start; padding: 28px 14px; }
            .donation-layout { grid-template-columns: 1fr; gap: 26px; }
            .movement-copy { max-width: none; text-align: center; }
            .movement-copy p { margin: 0 auto; }
            .donor-proof { justify-content: center; }
            .donation-card { max-width: 640px; width: 100%; margin: 0 auto; padding: 28px; }
        }

        @media (max-width: 575.98px) {
            .form-grid { grid-template-columns: 1fr; }
            .amount-grid { grid-template-columns: repeat(2, 1fr); }
            .amount-btn:nth-child(3n) { border-right: 1px solid var(--donation-border); }
            .amount-btn:nth-child(2n) { border-right: 0; }
            .amount-btn:nth-last-child(-n+3) { border-bottom: 1px solid var(--donation-border); }
            .amount-btn:nth-last-child(-n+2) { border-bottom: 0; }
            .card-header-row { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
<main class="page-shell">
    <div class="donation-layout">
        <section class="movement-copy" aria-label="Donation impact">
            <h1>Support the Movement</h1>
            <p>Your contribution fuels our global summits, educational programs, and humanitarian aid. 100% of public donations go directly to our peace initiatives. Together, we can fund the future of harmony.</p>
            <div class="donor-proof">
                <div class="avatar-stack" aria-hidden="true">
                    <div class="avatar">A</div>
                    <div class="avatar">M</div>
                    <div class="avatar">S</div>
                </div>
                <span>Joined by 45,000+ donors</span>
            </div>
        </section>

        <section class="donation-card" aria-label="Secure donation form">
            <div class="card-header-row">
                <h2>Secure Donation</h2>
                <span class="secure-badge">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 3l7 3v5c0 4.8-2.9 8.7-7 10-4.1-1.3-7-5.2-7-10V6l7-3z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M9 12l2 2 4-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    100% Secure
                </span>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="api-errors" class="alert alert-danger d-none"></div>

            <form id="donation-form" action="{{ route('donation.submit') }}" method="POST" data-payment-endpoint="{{ route('donation.submit') }}">
                <div class="type-toggle" role="group" aria-label="Donation type">
                    <input type="radio" name="payment_type" id="one_time" value="one_time" autocomplete="off" {{ old('payment_type', 'one_time') === 'one_time' ? 'checked' : '' }}>
                    <label for="one_time">One-time</label>

                    <input type="radio" name="payment_type" id="monthly" value="monthly" autocomplete="off" {{ old('payment_type') === 'monthly' ? 'checked' : '' }}>
                    <label for="monthly">Monthly</label>
                </div>

                <div class="form-grid">
                    <input type="text" name="full_name" class="donation-control" value="{{ old('full_name') }}" placeholder="Full Name*" required>
                    <input type="email" name="email" class="donation-control" value="{{ old('email') }}" placeholder="Email Address*" required>
                    <input type="text" name="country" class="donation-control" value="{{ old('country') }}" placeholder="Country*" required>
                    <input type="text" name="phone" class="donation-control" value="{{ old('phone') }}" placeholder="Phone (optional)">
                    <select id="currency" name="currency" class="donation-control" required>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->code }}" {{ old('currency', 'USD') === $currency->code ? 'selected' : '' }}>
                                {{ $currency->code }}
                            </option>
                        @endforeach
                    </select>
                    <div class="amount-input-wrap">
                        <span id="currency-symbol" class="amount-symbol">$</span>
                        <input id="amount" type="number" min="1" step="0.01" name="amount" class="donation-control" value="{{ old('amount') }}" placeholder="Custom Amount" required>
                    </div>
                </div>

                <div id="amount-buttons" class="amount-grid"></div>

                <button id="donation-submit" type="submit" class="donate-button">
                    Donate for Peace <span class="heart">&#9825;</span>
                </button>

                <p class="tax-note">Tax-deductible under 501(c)(3) regulations. Receipt sent instantly via email.</p>
            </form>
        </section>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    const currencyConfig = @json($currencyConfig);
    const oldAmount = @json(old('amount'));

    function amountLabel(symbol, amount) {
        return (symbol || '') + amount;
    }

    function renderAmountButtons() {
        const code = $('#currency').val();
        const config = currencyConfig[code] || { symbol: '', preset_amounts: [] };
        const symbol = config.symbol || code;
        const selectedAmount = $('#amount').val() || oldAmount || '';
        const presets = (config.preset_amounts || []).slice(0, 5);

        $('#currency-symbol').text(symbol);
        $('#amount-buttons').empty();

        presets.forEach(function(amount) {
            const active = String(selectedAmount) === String(amount) ? ' active' : '';
            $('#amount-buttons').append(
                '<button type="button" class="amount-btn' + active + '" data-amount="' + amount + '">' +
                amountLabel(symbol, amount) +
                '</button>'
            );
        });

        $('#amount-buttons').append('<button type="button" class="amount-btn amount-other" data-other="1">Other</button>');

        if (!$('#amount').val() && presets.length > 0) {
            const defaultAmount = presets[Math.min(1, presets.length - 1)];
            $('#amount').val(defaultAmount);
            $('.amount-btn[data-amount="' + defaultAmount + '"]').addClass('active');
        }
    }

    $(function() {
        renderAmountButtons();

        $('#currency').on('change', function() {
            $('#amount').val('');
            renderAmountButtons();
        });

        $(document).on('click', '.amount-btn[data-amount]', function() {
            $('.amount-btn').removeClass('active');
            $(this).addClass('active');
            $('#amount').val($(this).data('amount')).trigger('focus');
        });

        $(document).on('click', '.amount-other', function() {
            $('.amount-btn').removeClass('active');
            $(this).addClass('active');
            $('#amount').val('').trigger('focus');
        });

        $('#amount').on('input', function() {
            $('.amount-btn').removeClass('active');
            $('.amount-btn[data-amount="' + $(this).val() + '"]').addClass('active');
            if (!$('.amount-btn.active').length && $(this).val()) {
                $('.amount-other').addClass('active');
            }
        });

        $('#donation-form').on('submit', function(event) {
            event.preventDefault();

            const $form = $(this);
            const $button = $('#donation-submit');
            const $errors = $('#api-errors');

            $errors.addClass('d-none').empty();
            $button.prop('disabled', true).text('Redirecting to Stripe...');

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.data && response.data.checkout_url) {
                        window.location.href = response.data.checkout_url;
                        return;
                    }

                    $errors.removeClass('d-none').text('Unable to start payment right now. Please try again.');
                    $button.prop('disabled', false).html('Donate for Peace <span class="heart">&#9825;</span>');
                },
                error: function(xhr) {
                    const response = xhr.responseJSON || {};
                    const messages = [];

                    if (response.errors) {
                        Object.keys(response.errors).forEach(function(field) {
                            response.errors[field].forEach(function(message) {
                                messages.push(message);
                            });
                        });
                    }

                    if (!messages.length && response.message) {
                        messages.push(response.message);
                    }

                    $errors.removeClass('d-none').html(messages.length ? messages.join('<br>') : 'Unable to start payment right now. Please try again.');
                    $button.prop('disabled', false).html('Donate for Peace <span class="heart">&#9825;</span>');
                }
            });
        });
    });
</script>
</body>
</html>
