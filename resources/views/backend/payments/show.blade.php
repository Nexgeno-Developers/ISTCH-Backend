@extends('backend.layouts.app')

@section('content')
<div class="page-title-head d-flex align-items-center gap-2">
    <div class="flex-grow-1">
        <h4 class="fs-16 text-uppercase fw-bold mb-0">Payment #{{ $payment->id }}</h4>
    </div>
    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-secondary">Back</a>
</div>

@include('backend.includes.alert-message')

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header border-bottom border-dashed">
                <h5 class="mb-0">Payment Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Donor</strong>
                        <div>{{ $payment->full_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Email</strong>
                        <div>{{ $payment->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Phone</strong>
                        <div>{{ $payment->phone ?: '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Country</strong>
                        <div>{{ $payment->country }}</div>
                    </div>
                    <div class="col-md-4">
                        <strong>Type</strong>
                        <div>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</div>
                    </div>
                    <div class="col-md-4">
                        <strong>Status</strong>
                        <div>{{ ucfirst($payment->payment_status) }}</div>
                    </div>
                    <div class="col-md-4">
                        <strong>Amount</strong>
                        <div>{{ $payment->currency }} {{ number_format((float) $payment->amount, 2) }}</div>
                    </div>
                    <div class="col-md-4">
                        <strong>USD Amount</strong>
                        <div>USD {{ number_format((float) $payment->usd_amount, 2) }}</div>
                    </div>
                    <div class="col-md-4">
                        <strong>Exchange Rate</strong>
                        <div>{{ $payment->exchange_rate }}</div>
                    </div>
                    <div class="col-md-4">
                        <strong>Paid At</strong>
                        <div>{{ $payment->paid_at ? formatDatetime($payment->paid_at) : '-' }}</div>
                    </div>
                    <div class="col-md-12">
                        <strong>Payment Group ID</strong>
                        <div>{{ $payment->payment_group_id ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom border-dashed">
                <h5 class="mb-0">Recurring History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Invoice</th>
                                <th>Paid At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ ucfirst($row->payment_status) }}</td>
                                    <td>{{ $row->currency }} {{ number_format((float) $row->amount, 2) }}</td>
                                    <td>{{ $row->stripe_invoice_id ?: '-' }}</td>
                                    <td>{{ $row->paid_at ? formatDatetime($row->paid_at) : '-' }}</td>
                                    <td>
                                        <a href="{{ route('payments.show', $row->id) }}" class="link-reset fs-20 p-1" title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No recurring history found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header border-bottom border-dashed">
                <h5 class="mb-0">Stripe References</h5>
            </div>
            <div class="card-body">
                <p><strong>Customer</strong><br>{{ $payment->stripe_customer_id ?: '-' }}</p>
                <p><strong>Checkout Session</strong><br>{{ $payment->stripe_checkout_session_id ?: '-' }}</p>
                <p><strong>Payment Intent</strong><br>{{ $payment->stripe_payment_intent_id ?: '-' }}</p>
                <p><strong>Subscription</strong><br>{{ $payment->stripe_subscription_id ?: '-' }}</p>
                <p><strong>Invoice</strong><br>{{ $payment->stripe_invoice_id ?: '-' }}</p>
                <p><strong>Webhook Received</strong><br>{{ $payment->webhook_received_at ? formatDatetime($payment->webhook_received_at) : '-' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom border-dashed">
                <h5 class="mb-0">Meta</h5>
            </div>
            <div class="card-body">
                <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($payment->meta, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>
</div>
@endsection
