<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['accountReceivable.submission.salesOrder', 'progress', 'accountsPayable']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('accountReceivable.submission.salesOrder', function ($q) use ($search) {
                $q->where('so_number', 'like', "%{$search}%")
                    ->orWhere('so_name', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->get();

        $submissionIds = $orders
            ->pluck('accountReceivable.sales_order_submission_id')
            ->filter()
            ->values();

        $checklistDoneCounts = [];
        if ($submissionIds->isNotEmpty()) {
            $checklistDoneCounts = DB::table('sales_order_submission_player_checks')
                ->select('sales_order_submission_id', DB::raw('SUM(CASE WHEN is_done = 1 THEN 1 ELSE 0 END) AS done_count'))
                ->whereIn('sales_order_submission_id', $submissionIds)
                ->groupBy('sales_order_submission_id')
                ->pluck('done_count', 'sales_order_submission_id')
                ->map(fn ($value) => (int) $value)
                ->all();
        }

        return view('orders.Orders_page', compact('orders', 'checklistDoneCounts'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ongoing,ready_for_delivery,completed,claimed',
            'production_notes' => 'nullable|string',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;

        if ($request->production_notes) {
            $order->production_notes = $request->production_notes;
        }

        // Update timestamps based on status
        if ($request->status === 'completed' && ! $order->completed_at) {
            $order->completed_at = now();
        } elseif ($request->status === 'claimed' && ! $order->claimed_at) {
            $order->claimed_at = now();
        }

        $order->save();

        ActivityLog::log('update', "Updated order {$order->order_number} status to: {$request->status}", 'Order', $order->id);

        return redirect()->route('orders.index')
            ->with('success', 'Order status updated successfully!');
    }

    public function markCompleted($id)
    {
        $order = Order::findOrFail($id);
        $order->load('accountReceivable');

        $ar = $order->accountReceivable;
        $isFullyPaid = $ar && ($ar->status === 'paid' || ($ar->paid_amount >= $ar->total_amount));
        if (! $isFullyPaid) {
            return redirect()->route('orders.index')
                ->with('error', 'Order must be fully paid before marking as completed.');
        }

        // Mark order as completed
        $order->status = 'completed';
        $order->completed_at = now();
        $order->save();

        ActivityLog::log('update', "Marked order {$order->order_number} as completed", 'Order', $order->id);

        return redirect()->route('orders.index')
            ->with('success', 'Order marked as completed successfully!');
    }

    public function playerChecklist($id)
    {
        $order = Order::with(['accountReceivable.submission.salesOrder'])->findOrFail($id);

        $submission = $order->accountReceivable?->submission;
        if (! $submission) {
            return redirect()->route('orders.index')->with('error', 'This order has no submission data.');
        }

        $players = $submission->players ?? [];
        $checks = DB::table('sales_order_submission_player_checks')
            ->where('sales_order_submission_id', $submission->id)
            ->get()
            ->keyBy('player_index');

        $playerRows = [];
        foreach ($players as $index => $player) {
            $check = $checks->get($index);
            $playerRows[] = [
                'index' => $index,
                'product_id' => $player['product_id'] ?? null,
                'jersey_name' => $player['jersey_name'] ?? '',
                'jersey_number' => $player['jersey_number'] ?? '',
                'jersey_size' => $player['jersey_size'] ?? '',
                'is_done' => (bool) ($check->is_done ?? false),
                'done_at' => $check->done_at ?? null,
                'notes' => $check->notes ?? '',
            ];
        }

        $doneCount = collect($playerRows)->where('is_done', true)->count();
        $totalCount = count($playerRows);

        $productIds = collect($playerRows)->pluck('product_id')->filter()->unique()->values();
        $productsById = $productIds->isNotEmpty()
            ? Product::whereIn('id', $productIds)->get()->keyBy('id')
            : collect();

        return view('orders.player_checklist', compact(
            'order',
            'submission',
            'playerRows',
            'doneCount',
            'totalCount',
            'productsById',
        ));
    }

    public function playerChecklistsIndex(Request $request)
    {
        $query = Order::with(['accountReceivable.submission.salesOrder'])
            ->whereIn('status', ['ongoing', 'ready_for_delivery']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('accountReceivable.submission.salesOrder', function ($subQ) use ($search) {
                        $subQ->where('so_number', 'like', "%{$search}%")
                            ->orWhere('so_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        $submissionIds = $orders
            ->pluck('accountReceivable.sales_order_submission_id')
            ->filter()
            ->values();

        $doneCounts = [];
        if ($submissionIds->isNotEmpty()) {
            $doneCounts = DB::table('sales_order_submission_player_checks')
                ->select('sales_order_submission_id', DB::raw('SUM(CASE WHEN is_done = 1 THEN 1 ELSE 0 END) AS done_count'))
                ->whereIn('sales_order_submission_id', $submissionIds)
                ->groupBy('sales_order_submission_id')
                ->pluck('done_count', 'sales_order_submission_id')
                ->map(fn ($value) => (int) $value)
                ->all();
        }

        $items = $orders->map(function (Order $order) use ($doneCounts) {
            $submission = $order->accountReceivable?->submission;
            $submissionId = $order->accountReceivable?->sales_order_submission_id ?? $submission?->id;
            $totalPlayers = is_array($submission?->players ?? null) ? count($submission->players) : 0;
            $donePlayers = ($submissionId && isset($doneCounts[$submissionId])) ? (int) $doneCounts[$submissionId] : 0;

            return [
                'order' => $order,
                'submission' => $submission,
                'total_players' => $totalPlayers,
                'done_players' => min($donePlayers, $totalPlayers),
            ];
        });

        return view('orders.player_checklists_index', compact('items'));
    }

    public function updatePlayerChecklist(Request $request, $id)
    {
        $validated = $request->validate([
            'player_index' => 'required|integer|min:0',
            'is_done' => 'nullable|boolean',
            'notes' => 'nullable|string|max:2000',
        ]);

        $order = Order::with(['accountReceivable.submission'])->findOrFail($id);
        $submission = $order->accountReceivable?->submission;
        if (! $submission) {
            return redirect()->route('orders.index')->with('error', 'This order has no submission data.');
        }

        $players = $submission->players ?? [];
        $totalCount = count($players);

        $playerIndex = (int) $validated['player_index'];
        if ($playerIndex < 0 || $playerIndex >= $totalCount) {
            return back()->withErrors(['player_index' => 'Invalid player index.']);
        }

        $existing = DB::table('sales_order_submission_player_checks')
            ->where('sales_order_submission_id', $submission->id)
            ->where('player_index', $playerIndex)
            ->first();

        $nextIsDone = (bool) ($existing->is_done ?? false);
        if ($request->has('is_done')) {
            $nextIsDone = (bool) $request->boolean('is_done');
        }

        $nextNotes = $existing->notes ?? null;
        if ($request->has('notes')) {
            $trimmed = trim((string) $request->input('notes', ''));
            $nextNotes = $trimmed === '' ? null : $trimmed;
        }

        $now = now();
        $doneAt = null;
        if ($nextIsDone) {
            $doneAt = ($existing && $existing->done_at) ? \Illuminate\Support\Carbon::parse($existing->done_at) : $now;
        }
        $payload = [
            'is_done' => $nextIsDone,
            'done_at' => $doneAt,
            'notes' => $nextNotes,
            'updated_at' => $now,
        ];

        if ($existing) {
            DB::table('sales_order_submission_player_checks')
                ->where('sales_order_submission_id', $submission->id)
                ->where('player_index', $playerIndex)
                ->update($payload);
        } else {
            DB::table('sales_order_submission_player_checks')->insert([
                'sales_order_submission_id' => $submission->id,
                'player_index' => $playerIndex,
                'is_done' => $payload['is_done'],
                'done_at' => $payload['done_at'],
                'notes' => $payload['notes'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        ActivityLog::log('update', "Updated player checklist for order {$order->order_number} (player #{$playerIndex})", 'Order', $order->id);

        $doneCount = (int) DB::table('sales_order_submission_player_checks')
            ->where('sales_order_submission_id', $submission->id)
            ->where('is_done', true)
            ->count();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'player_index' => $playerIndex,
                'is_done' => $nextIsDone,
                'done_at' => $doneAt ? $doneAt->toDateTimeString() : null,
                'notes' => $nextNotes ?? '',
                'done_count' => $doneCount,
                'total_count' => $totalCount,
            ]);
        }

        return back()->with('success', 'Checklist updated.');
    }

    public function bulkUpdatePlayerChecklist(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:complete_all,reset_all',
        ]);

        $order = Order::with(['accountReceivable.submission'])->findOrFail($id);
        $submission = $order->accountReceivable?->submission;
        if (! $submission) {
            return redirect()->route('orders.index')->with('error', 'This order has no submission data.');
        }

        $players = $submission->players ?? [];
        $totalCount = count($players);
        $now = now();

        if ($validated['action'] === 'complete_all') {
            $rows = [];
            for ($i = 0; $i < $totalCount; $i++) {
                $rows[] = [
                    'sales_order_submission_id' => $submission->id,
                    'player_index' => $i,
                    'is_done' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (! empty($rows)) {
                DB::table('sales_order_submission_player_checks')->insertOrIgnore($rows);
            }

            DB::table('sales_order_submission_player_checks')
                ->where('sales_order_submission_id', $submission->id)
                ->update([
                    'is_done' => true,
                    'done_at' => $now,
                    'updated_at' => $now,
                ]);

            ActivityLog::log('update', "Marked all players as done for order {$order->order_number}", 'Order', $order->id);
        }

        if ($validated['action'] === 'reset_all') {
            DB::table('sales_order_submission_player_checks')
                ->where('sales_order_submission_id', $submission->id)
                ->update([
                    'is_done' => false,
                    'done_at' => null,
                    'updated_at' => $now,
                ]);

            ActivityLog::log('update', "Reset player checklist for order {$order->order_number}", 'Order', $order->id);
        }

        $doneCount = (int) DB::table('sales_order_submission_player_checks')
            ->where('sales_order_submission_id', $submission->id)
            ->where('is_done', true)
            ->count();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'done_count' => $doneCount,
                'total_count' => $totalCount,
            ]);
        }

        return back()->with('success', 'Checklist updated.');
    }
}
