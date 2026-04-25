@extends('layouts.navbar')

@section('title', 'Player Checklist')

@push('styles')
<style>
    .bw-page .progress {
        background: rgba(0, 0, 0, 0.08);
        border-radius: 999px;
        overflow: hidden;
    }

    .bw-page .progress-bar {
        background: #111;
    }

    .bw-page .table thead th {
        color: rgba(0, 0, 0, 0.75);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom-color: rgba(0, 0, 0, 0.10);
    }
</style>
@endpush

@section('content')
<div class="bw-page">
@php
    $percent = $totalCount > 0 ? (int) round(($doneCount / $totalCount) * 100) : 0;
    $productOptions = $productsById->map(fn ($p) => $p->name)->all();
    $players = collect($playerRows)->map(function ($row) use ($productsById) {
        $productName = 'Unknown Product';
        if (!empty($row['product_id']) && $productsById->has($row['product_id'])) {
            $productName = $productsById->get($row['product_id'])->name;
        }
        $row['product_name'] = $productName;
        return $row;
    })->values();
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <h1 class="h4 mb-0"><i class="bi bi-check2-square"></i> Player Checklist</h1>
            <span class="badge bg-light text-dark border">{{ $order->order_number }}</span>
            <span class="badge text-bg-dark">{{ $order->accountReceivable->submission->salesOrder->so_number }}</span>
        </div>
        <div class="text-muted small mt-1">
            {{ $order->accountReceivable->submission->salesOrder->so_name }}
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <button type="button" class="btn btn-dark" id="bulkCompleteBtn">
            <i class="bi bi-check2-all"></i> Mark All Done
        </button>
        <button type="button" class="btn btn-outline-dark" id="bulkResetBtn">
            <i class="bi bi-arrow-counterclockwise"></i> Reset
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-2">
            <div class="fw-semibold">Progress</div>
            <div class="text-muted small" id="progressText">{{ $doneCount }} / {{ $totalCount }} done ({{ $percent }}%)</div>
        </div>
        <div class="progress" style="height: 12px;">
            <div class="progress-bar" id="progressBar" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
            <div class="col-md-6">
                <label class="form-label"><i class="bi bi-search"></i> Search</label>
                <input type="text" class="form-control" id="playerSearch" placeholder="Name / number / size / product">
            </div>
            <div class="col-md-4">
                <label class="form-label"><i class="bi bi-filter"></i> Product</label>
                <select class="form-select" id="productFilter">
                    <option value="">All Products</option>
                    @foreach($productOptions as $productName)
                        <option value="{{ $productName }}">{{ $productName }}</option>
                    @endforeach
                    <option value="Unknown Product">Unknown Product</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-outline-secondary w-100" id="clearFiltersBtn">
                    <i class="bi bi-x-circle"></i> Clear
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">Done</th>
                        <th style="width: 70px;">#</th>
                        <th>Product</th>
                        <th>Jersey Name</th>
                        <th style="width: 120px;">Number</th>
                        <th style="width: 120px;">Size</th>
                        <th>Notes</th>
                        <th style="width: 170px;">Finished At</th>
                    </tr>
                </thead>
                <tbody id="playersTbody">
                    @foreach($players as $row)
                        @php
                            $rowText = strtolower(trim(($row['product_name'] ?? '') . ' ' . ($row['jersey_name'] ?? '') . ' ' . ($row['jersey_number'] ?? '') . ' ' . ($row['jersey_size'] ?? '')));
                        @endphp
                        <tr data-player-row="1" data-product="{{ $row['product_name'] }}" data-search="{{ $rowText }}" class="{{ $row['is_done'] ? 'table-active' : '' }}">
                            <td>
                                <div class="form-check m-0">
                                    <input class="form-check-input player-done-toggle" type="checkbox" data-player-index="{{ $row['index'] }}" {{ $row['is_done'] ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="text-muted">{{ $row['index'] + 1 }}</td>
                            <td class="fw-semibold text-dark">{{ $row['product_name'] }}</td>
                            <td class="fw-semibold">{{ $row['jersey_name'] }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $row['jersey_number'] }}</span></td>
                            <td class="text-muted">{{ $row['jersey_size'] }}</td>
                            <td>
                                <input type="text" class="form-control form-control-sm player-notes" data-player-index="{{ $row['index'] }}" value="{{ $row['notes'] }}">
                            </td>
                            <td class="text-muted small finished-at" data-player-index="{{ $row['index'] }}">
                                @if($row['is_done'] && $row['done_at'])
                                    {{ \Illuminate\Support\Carbon::parse($row['done_at'])->format('M d, Y h:i A') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if($players->count() === 0)
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">No players found for this order.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const csrfToken = @json(csrf_token());
    const updateUrl = @json(route('orders.player-checklist.update', $order->id));
    const bulkUrl = @json(route('orders.player-checklist.bulk', $order->id));

    const progressText = document.getElementById('progressText');
    const progressBar = document.getElementById('progressBar');

    function formatServerDateTime(value) {
        if (!value) return '-';
        const date = new Date(String(value).replace(' ', 'T'));
        if (Number.isNaN(date.getTime())) return String(value);
        return date.toLocaleString();
    }

    function setProgress(doneCount, totalCount) {
        const percent = totalCount > 0 ? Math.round((doneCount / totalCount) * 100) : 0;
        progressText.textContent = `${doneCount} / ${totalCount} done (${percent}%)`;
        progressBar.style.width = `${percent}%`;
        progressBar.setAttribute('aria-valuenow', String(percent));
    }

    function applyFilters() {
        const search = (document.getElementById('playerSearch').value || '').trim().toLowerCase();
        const product = document.getElementById('productFilter').value || '';

        document.querySelectorAll('tr[data-player-row="1"]').forEach(row => {
            const rowSearch = row.getAttribute('data-search') || '';
            const rowProduct = row.getAttribute('data-product') || '';

            const matchSearch = !search || rowSearch.includes(search);
            const matchProduct = !product || rowProduct === product;
            row.style.display = (matchSearch && matchProduct) ? '' : 'none';
        });
    }

    document.getElementById('playerSearch').addEventListener('input', applyFilters);
    document.getElementById('productFilter').addEventListener('change', applyFilters);
    document.getElementById('clearFiltersBtn').addEventListener('click', () => {
        document.getElementById('playerSearch').value = '';
        document.getElementById('productFilter').value = '';
        applyFilters();
    });

    async function postJson(url, payload) {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(payload),
        });

        const data = await res.json().catch(() => null);
        if (!res.ok) {
            const message = (data && (data.message || data.error)) ? (data.message || data.error) : 'Request failed.';
            throw new Error(message);
        }
        return data;
    }

    document.querySelectorAll('.player-done-toggle').forEach(input => {
        input.addEventListener('change', async (e) => {
            const playerIndex = Number(e.target.getAttribute('data-player-index'));
            const isDone = e.target.checked;
            e.target.disabled = true;

            try {
                const data = await postJson(updateUrl, { player_index: playerIndex, is_done: isDone });
                setProgress(data.done_count, data.total_count);

                const row = e.target.closest('tr');
                if (row) {
                    row.classList.toggle('table-active', data.is_done);
                }

                const finishedEl = document.querySelector(`.finished-at[data-player-index="${playerIndex}"]`);
                if (finishedEl) {
                    finishedEl.textContent = data.is_done ? formatServerDateTime(data.done_at) : '-';
                }
            } catch (err) {
                e.target.checked = !isDone;
                alert(err.message || 'Failed to update.');
            } finally {
                e.target.disabled = false;
            }
        });
    });

    const notesTimers = new Map();
    document.querySelectorAll('.player-notes').forEach(input => {
        input.addEventListener('input', (e) => {
            const playerIndex = Number(e.target.getAttribute('data-player-index'));
            const value = e.target.value;

            if (notesTimers.has(playerIndex)) {
                clearTimeout(notesTimers.get(playerIndex));
            }

            notesTimers.set(playerIndex, setTimeout(async () => {
                e.target.disabled = true;
                try {
                    await postJson(updateUrl, { player_index: playerIndex, notes: value });
                } catch (err) {
                    alert(err.message || 'Failed to save notes.');
                } finally {
                    e.target.disabled = false;
                }
            }, 450));
        });
    });

    document.getElementById('bulkCompleteBtn').addEventListener('click', async () => {
        const btn = document.getElementById('bulkCompleteBtn');
        btn.disabled = true;
        try {
            const data = await postJson(bulkUrl, { action: 'complete_all' });
            setProgress(data.done_count, data.total_count);
            const nowLabel = new Date().toLocaleString();
            document.querySelectorAll('.player-done-toggle').forEach(cb => {
                cb.checked = true;
                const row = cb.closest('tr');
                if (row) row.classList.add('table-active');
            });
            document.querySelectorAll('.finished-at').forEach(el => {
                el.textContent = nowLabel;
            });
        } catch (err) {
            alert(err.message || 'Failed to update.');
        } finally {
            btn.disabled = false;
        }
    });

    document.getElementById('bulkResetBtn').addEventListener('click', async () => {
        const btn = document.getElementById('bulkResetBtn');
        btn.disabled = true;
        try {
            const data = await postJson(bulkUrl, { action: 'reset_all' });
            setProgress(data.done_count, data.total_count);
            document.querySelectorAll('.player-done-toggle').forEach(cb => {
                cb.checked = false;
                const row = cb.closest('tr');
                if (row) row.classList.remove('table-active');
            });
            document.querySelectorAll('.finished-at').forEach(el => {
                el.textContent = '-';
            });
        } catch (err) {
            alert(err.message || 'Failed to update.');
        } finally {
            btn.disabled = false;
        }
    });
</script>
@endsection
</div>
