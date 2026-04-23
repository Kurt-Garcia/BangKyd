<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\SalesOrderSubmission;
use App\Models\AccountReceivable;
use App\Models\AccountPayable;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Sales Orders Stats
        $totalSalesOrders = SalesOrder::count();
        $pendingSalesOrders = SalesOrder::whereDoesntHave('submissions')->count();
        
        // Submissions/Orders Stats
        $totalSubmissions = SalesOrderSubmission::count();
        $unconfirmedSubmissions = SalesOrderSubmission::whereDoesntHave('accountReceivable')->count();
        
        // Accounts Receivable Stats
        $totalAR = AccountReceivable::count();
        $pendingPayments = AccountReceivable::where('status', 'pending')->count();
        $partialPayments = AccountReceivable::where('status', 'partial')->count();
        $totalOutstanding = AccountReceivable::where('status', '!=', 'paid')->sum('balance');
        $totalReceived = AccountReceivable::sum('paid_amount');
        
        // Orders Stats
        $totalOrders = Order::count();
        $ongoingOrders = Order::where('status', 'ongoing')->count();
        $readyForDelivery = Order::where('status', 'ready_for_delivery')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        
        // Accounts Payable Stats
        $totalAP = AccountPayable::count();
        $pendingAP = AccountPayable::where('status', 'pending')->count();
        $partialAP = AccountPayable::where('status', 'partial')->count();
        $totalAPOutstanding = AccountPayable::where('status', '!=', 'paid')->sum('balance');
        $totalAPPaid = AccountPayable::sum('paid_amount');
        
        // Recent Activity
        $recentSubmissions = SalesOrderSubmission::with('salesOrder.product')
            ->latest()
            ->take(5)
            ->get();
        
        $recentPayments = AccountReceivable::with('submission.salesOrder.product', 'payments')
            ->whereHas('payments')
            ->latest()
            ->take(5)
            ->get();
        
        // Orders in production with progress
        $ordersInProduction = Order::with(['accountReceivable.submission.salesOrder', 'progress'])
            ->where('status', 'ongoing')
            ->latest()
            ->take(5)
            ->get();

        $trendDays = 14;
        $trendEnd = Carbon::today();
        $trendStart = $trendEnd->copy()->subDays($trendDays - 1);
        $trendStartTs = $trendStart->copy()->startOfDay();
        $trendEndTs = $trendEnd->copy()->endOfDay();

        $submissionsByDate = SalesOrderSubmission::query()
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c, COALESCE(SUM(total_amount), 0) as s')
            ->whereBetween('created_at', [$trendStartTs, $trendEndTs])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('d')
            ->get()
            ->keyBy('d');

        $trendLabels = [];
        $trendCounts = [];
        $trendRevenue = [];
        for ($i = 0; $i < $trendDays; $i++) {
            $day = $trendStart->copy()->addDays($i);
            $key = $day->toDateString();
            $trendLabels[] = $day->format('M j');
            $trendCounts[] = (int) ($submissionsByDate[$key]->c ?? 0);
            $trendRevenue[] = (float) ($submissionsByDate[$key]->s ?? 0);
        }

        $prevStart = $trendStart->copy()->subDays($trendDays)->startOfDay();
        $prevEnd = $trendStart->copy()->subDay()->endOfDay();
        $prevRevenue = (float) SalesOrderSubmission::whereBetween('created_at', [$prevStart, $prevEnd])->sum('total_amount');
        $currRevenue = (float) array_sum($trendRevenue);

        $revenueDelta = $prevRevenue > 0
            ? (($currRevenue - $prevRevenue) / $prevRevenue) * 100
            : ($currRevenue > 0 ? 100 : 0);

        return view('dashboard', compact(
            'totalSalesOrders',
            'pendingSalesOrders',
            'totalSubmissions',
            'unconfirmedSubmissions',
            'totalAR',
            'pendingPayments',
            'partialPayments',
            'totalOutstanding',
            'totalReceived',
            'totalOrders',
            'ongoingOrders',
            'readyForDelivery',
            'completedOrders',
            'totalAP',
            'pendingAP',
            'partialAP',
            'totalAPOutstanding',
            'totalAPPaid',
            'recentSubmissions',
            'recentPayments',
            'ordersInProduction',
            'trendLabels',
            'trendCounts',
            'trendRevenue',
            'currRevenue',
            'prevRevenue',
            'revenueDelta'
        ));
    }
}
