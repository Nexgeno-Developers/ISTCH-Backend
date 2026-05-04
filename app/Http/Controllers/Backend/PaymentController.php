<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $moduleName;
    protected $folderName;
    protected $routeName;

    public function __construct()
    {
        $this->moduleName = 'Payments';
        $this->folderName = 'payments';
        $this->routeName = 'payments';
        view()->share('moduleName', $this->moduleName);
        view()->share('folderName', $this->folderName);
        view()->share('routeName', $this->routeName);
    }

    public function index(Request $request)
    {
        $query = Payment::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($query) use ($search) {
                $query->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhere('payment_group_id', 'like', "%{$search}%")
                    ->orWhere('stripe_checkout_session_id', 'like', "%{$search}%")
                    ->orWhere('stripe_payment_intent_id', 'like', "%{$search}%")
                    ->orWhere('stripe_subscription_id', 'like', "%{$search}%")
                    ->orWhere('stripe_invoice_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('currency')) {
            $query->where('currency', strtoupper((string) $request->currency));
        }

        if ($request->filled('payment_group_id')) {
            $query->where('payment_group_id', $request->payment_group_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $pageData = $query
            ->orderByDesc('created_at')
            ->paginate(config('custom.pagination_per_page'))
            ->appends($request->query());

        $statuses = [
            Payment::STATUS_PENDING,
            Payment::STATUS_PAID,
            Payment::STATUS_FAILED,
            Payment::STATUS_CANCELLED,
            Payment::STATUS_REFUNDED,
        ];

        $types = [
            Payment::TYPE_ONE_TIME,
            Payment::TYPE_MONTHLY,
        ];

        $currencies = Payment::query()
            ->select('currency')
            ->distinct()
            ->orderBy('currency')
            ->pluck('currency');

        return view('backend.' . $this->folderName . '.index', compact('pageData', 'statuses', 'types', 'currencies'));
    }

    public function show(Payment $payment)
    {
        $history = collect();
        if ($payment->payment_group_id) {
            $history = Payment::where('payment_group_id', $payment->payment_group_id)
                ->orderBy('created_at')
                ->get();
        }

        return view('backend.' . $this->folderName . '.show', compact('payment', 'history'));
    }
}
