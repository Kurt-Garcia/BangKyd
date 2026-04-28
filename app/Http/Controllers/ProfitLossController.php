<?php

namespace App\Http\Controllers;

use App\Models\AccountPayable;
use App\Models\AccountReceivable;
use App\Models\APPayment;
use App\Models\ARPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
{
    public function index(Request $request)
    {
        $basis = $request->string('basis')->lower()->toString();
        if (!in_array($basis, ['cash', 'accrual'], true)) {
            $basis = 'cash';
        }

        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->input('date_from'))->startOfDay()
            : now()->startOfMonth()->startOfDay();

        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->input('date_to'))->endOfDay()
            : now()->endOfMonth()->endOfDay();

        if ($dateFrom->greaterThan($dateTo)) {
            [$dateFrom, $dateTo] = [$dateTo->copy()->startOfDay(), $dateFrom->copy()->endOfDay()];
        }

        if ($basis === 'cash') {
            $revenueTotal = (float) ARPayment::query()
                ->whereBetween('paid_at', [$dateFrom, $dateTo])
                ->sum('amount');

            $expenseTotal = (float) APPayment::query()
                ->whereBetween('paid_at', [$dateFrom, $dateTo])
                ->sum('amount');

            $revenueByCustomer = DB::table('ar_payments as p')
                ->join('account_receivables as ar', 'ar.id', '=', 'p.account_receivable_id')
                ->join('sales_order_submissions as s', 's.id', '=', 'ar.sales_order_submission_id')
                ->join('sales_orders as so', 'so.id', '=', 's.sales_order_id')
                ->whereBetween('p.paid_at', [$dateFrom, $dateTo])
                ->selectRaw('so.so_name as customer, SUM(p.amount) as total')
                ->groupBy('so.so_name')
                ->orderByDesc('total')
                ->limit(10)
                ->get();

            $expenseByVendor = DB::table('ap_payments as p')
                ->join('accounts_payable as ap', 'ap.id', '=', 'p.account_payable_id')
                ->whereBetween('p.paid_at', [$dateFrom, $dateTo])
                ->selectRaw('ap.vendor_type as vendor_type, SUM(p.amount) as total')
                ->groupBy('ap.vendor_type')
                ->orderByDesc('total')
                ->get();

            $revenueLines = ARPayment::query()
                ->with(['accountReceivable.submission.salesOrder'])
                ->whereBetween('paid_at', [$dateFrom, $dateTo])
                ->orderByDesc('paid_at')
                ->limit(200)
                ->get();

            $expenseLines = APPayment::query()
                ->with(['accountPayable.order.accountReceivable.submission.salesOrder'])
                ->whereBetween('paid_at', [$dateFrom, $dateTo])
                ->orderByDesc('paid_at')
                ->limit(200)
                ->get();
        } else {
            $revenueTotal = (float) AccountReceivable::query()
                ->whereNotNull('confirmed_at')
                ->whereBetween('confirmed_at', [$dateFrom, $dateTo])
                ->sum('total_amount');

            $expenseTotal = (float) AccountPayable::query()
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('total_amount');

            $revenueByCustomer = DB::table('account_receivables as ar')
                ->join('sales_order_submissions as s', 's.id', '=', 'ar.sales_order_submission_id')
                ->join('sales_orders as so', 'so.id', '=', 's.sales_order_id')
                ->whereNotNull('ar.confirmed_at')
                ->whereBetween('ar.confirmed_at', [$dateFrom, $dateTo])
                ->selectRaw('so.so_name as customer, SUM(ar.total_amount) as total')
                ->groupBy('so.so_name')
                ->orderByDesc('total')
                ->limit(10)
                ->get();

            $expenseByVendor = DB::table('accounts_payable as ap')
                ->whereBetween('ap.created_at', [$dateFrom, $dateTo])
                ->selectRaw('ap.vendor_type as vendor_type, SUM(ap.total_amount) as total')
                ->groupBy('ap.vendor_type')
                ->orderByDesc('total')
                ->get();

            $revenueLines = AccountReceivable::query()
                ->with(['submission.salesOrder'])
                ->whereNotNull('confirmed_at')
                ->whereBetween('confirmed_at', [$dateFrom, $dateTo])
                ->orderByDesc('confirmed_at')
                ->limit(200)
                ->get();

            $expenseLines = AccountPayable::query()
                ->with(['order.accountReceivable.submission.salesOrder'])
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->orderByDesc('created_at')
                ->limit(200)
                ->get();
        }

        $netProfit = $revenueTotal - $expenseTotal;
        $marginPercent = $revenueTotal > 0 ? ($netProfit / $revenueTotal) * 100 : 0.0;

        $arOutstanding = (float) AccountReceivable::query()
            ->where('status', '!=', 'paid')
            ->whereNotNull('confirmed_at')
            ->where('confirmed_at', '<=', $dateTo)
            ->sum('balance');

        $apOutstanding = (float) AccountPayable::query()
            ->where('status', '!=', 'paid')
            ->where('created_at', '<=', $dateTo)
            ->sum('balance');

        return view('reports.profit_loss', [
            'basis' => $basis,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'revenueTotal' => $revenueTotal,
            'expenseTotal' => $expenseTotal,
            'netProfit' => $netProfit,
            'marginPercent' => $marginPercent,
            'revenueByCustomer' => $revenueByCustomer,
            'expenseByVendor' => $expenseByVendor,
            'revenueLines' => $revenueLines,
            'expenseLines' => $expenseLines,
            'arOutstanding' => $arOutstanding,
            'apOutstanding' => $apOutstanding,
        ]);
    }
}

