@extends('backend.layouts.app')

@section('content')
<div class="page-title-head d-flex align-items-center gap-2">
    <div class="flex-grow-1">
        <h4 class="fs-16 text-uppercase fw-bold mb-0">{{ $moduleName }}</h4>
    </div>
</div>

@include('backend.includes.alert-message')

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-bottom border-dashed align-items-center">
                <form class="row g-2 align-items-end">
                    @if(request('payment_group_id'))
                        <input type="hidden" name="payment_group_id" value="{{ request('payment_group_id') }}">
                    @endif
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Name, email, Stripe ID, group ID">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select name="payment_type" class="form-control">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" @selected(request('payment_type') === $type)>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="payment_status" class="form-control">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected(request('payment_status') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Currency</label>
                        <select name="currency" class="form-control">
                            <option value="">All</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency }}" @selected(request('currency') === $currency)>{{ $currency }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success btn-icon w-100">
                            <i class="ti ti-search"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button type="reset" class="btn btn-warning btn-icon w-100" onclick="window.location.href = '{{ route('payments.index') }}';">
                            <i class="ti ti-refresh"></i> Reset
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                @if(request('payment_group_id'))
                    <div class="alert alert-info py-2">
                        Showing payment history for group <strong>{{ request('payment_group_id') }}</strong>.
                    </div>
                @endif
                <div class="table-responsive-sm table-responsive">
                    <table class="table table-striped text-truncate">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Donor</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Group ID</th>
                                <th>Stripe Invoice</th>
                                <th>Paid At</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pageData as $index => $row)
                                <tr>
                                    <td>{{ $pageData->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $row->full_name }}</div>
                                        <div class="text-muted small">{{ $row->email }}</div>
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $row->payment_type)) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $row->payment_status === 'paid' ? 'success' : ($row->payment_status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($row->payment_status) }}
                                        </span>
                                    </td>
                                    <td>{{ $row->currency }} {{ number_format((float) $row->amount, 2) }}</td>
                                    <td>
                                        @if($row->payment_group_id)
                                            <a href="{{ route('payments.index', ['payment_group_id' => $row->payment_group_id]) }}">{{ $row->payment_group_id }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $row->stripe_invoice_id ?: '-' }}</td>
                                    <td>{{ $row->paid_at ? formatDatetime($row->paid_at) : '-' }}</td>
                                    <td>{{ formatDatetime($row->created_at) }}</td>
                                    <td>
                                        <a href="{{ route('payments.show', $row->id) }}" class="link-reset fs-20 p-1" title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">No payments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $pageData->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
