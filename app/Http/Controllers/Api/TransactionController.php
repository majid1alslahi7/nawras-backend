<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Appointment;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::with(['category', 'patient', 'enteredBy', 'appointment']);

        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('patient_id')) $query->where('patient_id', $request->patient_id);
        if ($request->filled('payment_method')) $query->where('payment_method', $request->payment_method);
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('transaction_date', [$request->date_from, $request->date_to]);
        } elseif ($request->get('filter') === 'today') {
            $query->whereDate('transaction_date', today());
        } elseif ($request->get('filter') === 'this_month') {
            $query->whereMonth('transaction_date', now()->month)->whereYear('transaction_date', now()->year);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                    ->orWhere('receipt_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('patient', fn ($patient) => $patient->where('full_name', 'LIKE', "%{$search}%"));
            });
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate($request->get('per_page', 30));

        return response()->json([
            'data' => TransactionResource::collection($transactions),
            'meta' => ['total' => $transactions->total(), 'page' => $transactions->currentPage(), 'last_page' => $transactions->lastPage()],
        ]);
    }

    public function store(TransactionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['total_amount'] = $data['amount'] - ($data['discount'] ?? 0) + ($data['tax'] ?? 0);
        $data['entered_by'] = auth()->id();
        $data['receipt_type'] = $data['receipt_type'] ?? ($data['type'] === 'إيراد' ? 'income_receipt' : 'expense_receipt');

        if ($request->hasFile('receipt_image')) {
            $data['receipt_image_path'] = $request->file('receipt_image')->store('receipts', 'public');
        }

        $transaction = Transaction::create($data);
        $transaction->load(['category', 'patient', 'enteredBy', 'appointment']);

        if ($transaction->appointment_id && $transaction->type === 'إيراد') {
            Appointment::whereKey($transaction->appointment_id)->update([
                'paid_transaction_id' => $transaction->id,
                'paid_at' => now(),
                'payment_status' => 'paid',
                'is_free' => false,
                'payment_notes' => $transaction->description,
            ]);
        }

        return response()->json([
            'message' => 'تم تسجيل المعاملة المالية بنجاح',
            'data' => new TransactionResource($transaction),
        ], 201);
    }

    public function show(Transaction $transaction): JsonResponse
    {
        $transaction->load(['category', 'patient', 'enteredBy', 'visit', 'appointment']);
        return response()->json(new TransactionResource($transaction));
    }

    public function update(TransactionUpdateRequest $request, Transaction $transaction): JsonResponse
    {
        $data = $request->validated();
        if (isset($data['amount']) || isset($data['discount']) || isset($data['tax'])) {
            $amount = $data['amount'] ?? $transaction->amount;
            $discount = $data['discount'] ?? $transaction->discount;
            $tax = $data['tax'] ?? $transaction->tax;
            $data['total_amount'] = $amount - $discount + $tax;
        }
        if ($request->hasFile('receipt_image')) {
            if ($transaction->receipt_image_path) {
                Storage::disk('public')->delete($transaction->receipt_image_path);
            }
            $data['receipt_image_path'] = $request->file('receipt_image')->store('receipts', 'public');
        }
        unset($data['receipt_image']);
        $transaction->update($data);

        if ($transaction->appointment_id && $transaction->type === 'إيراد') {
            Appointment::whereKey($transaction->appointment_id)->update([
                'paid_transaction_id' => $transaction->id,
                'paid_at' => now(),
                'payment_status' => 'paid',
                'is_free' => false,
                'payment_notes' => $transaction->description,
            ]);
        }

        return response()->json([
            'message' => 'تم تحديث المعاملة المالية',
            'data' => new TransactionResource($transaction->load(['category', 'patient', 'enteredBy', 'appointment'])),
        ]);
    }

    public function destroy(Transaction $transaction): JsonResponse
    {
        Appointment::where('paid_transaction_id', $transaction->id)->update([
            'paid_transaction_id' => null,
            'paid_at' => null,
            'payment_status' => 'unpaid',
            'payment_notes' => null,
        ]);
        if ($transaction->receipt_image_path) {
            Storage::disk('public')->delete($transaction->receipt_image_path);
        }

        $transaction->delete();

        return response()->json(['message' => 'تم حذف المعاملة المالية']);
    }

    public function receipts(string $type, Request $request): JsonResponse
    {
        abort_unless(in_array($type, ['income', 'expense'], true), 404);

        $transactions = Transaction::with(['category', 'patient', 'enteredBy'])
            ->where('type', $type === 'income' ? 'إيراد' : 'مصروف')
            ->latest('transaction_date')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'data' => TransactionResource::collection($transactions),
            'meta' => ['total' => $transactions->total(), 'page' => $transactions->currentPage(), 'last_page' => $transactions->lastPage()],
        ]);
    }

    public function receiptPdf(Transaction $transaction)
    {
        $transaction->load(['category', 'patient', 'enteredBy', 'appointment']);
        $pdf = Pdf::loadView('pdf.receipt', ['transaction' => $transaction])
            ->setPaper('a5', 'portrait');

        return $pdf->stream("receipt-{$transaction->receipt_number}.pdf");
    }

    public function dailySummary(): JsonResponse
    {
        $today = today();
        $summary = Transaction::whereDate('transaction_date', $today)
            ->selectRaw("
                SUM(CASE WHEN type = 'إيراد' THEN total_amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'مصروف' THEN total_amount ELSE 0 END) as total_expense,
                COUNT(*) as transaction_count
            ")->first();

        $byPaymentMethod = Transaction::whereDate('transaction_date', $today)
            ->where('type', 'إيراد')
            ->selectRaw('payment_method, SUM(total_amount) as total')
            ->groupBy('payment_method')->get();

        return response()->json([
            'date' => $today->format('Y-m-d'),
            'total_income' => $summary->total_income ?? 0,
            'total_expense' => $summary->total_expense ?? 0,
            'net_profit' => ($summary->total_income ?? 0) - ($summary->total_expense ?? 0),
            'transaction_count' => $summary->transaction_count ?? 0,
            'by_payment_method' => $byPaymentMethod,
        ]);
    }

    public function monthlySummary(Request $request): JsonResponse
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $summary = Transaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->selectRaw("
                SUM(CASE WHEN type = 'إيراد' THEN total_amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'مصروف' THEN total_amount ELSE 0 END) as total_expense,
                COUNT(DISTINCT DATE(transaction_date)) as working_days
            ")->first();

        $dailyBreakdown = Transaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->selectRaw("DATE(transaction_date) as date,
                SUM(CASE WHEN type = 'إيراد' THEN total_amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'مصروف' THEN total_amount ELSE 0 END) as expense")
            ->groupBy('date')->orderBy('date')->get();

        return response()->json([
            'month' => $month,
            'year' => $year,
            'total_income' => $summary->total_income ?? 0,
            'total_expense' => $summary->total_expense ?? 0,
            'net_profit' => ($summary->total_income ?? 0) - ($summary->total_expense ?? 0),
            'working_days' => $summary->working_days ?? 0,
            'daily_breakdown' => $dailyBreakdown,
        ]);
    }
}
