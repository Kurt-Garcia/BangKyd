@extends('layouts.navbar')

@section('title', 'Accounts Payable')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-wallet2"></i> Accounts Payable</h1>
    <div>
        <span class="badge bg-primary">{{ $orders->count() }} Total Orders</span>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('accounts-payable.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label"><i class="bi bi-search"></i> Search</label>
                    <input type="text" class="form-control" name="search" placeholder="Order/SO Number or Customer" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                        @if(request()->hasAny(['search']))
                            <a href="{{ route('accounts-payable.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Clear</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($orders as $order)
    @php
        $downpayment = $order->accountReceivable ? $order->accountReceivable->paid_amount : 0;
        $totalPayables = $order->accountsPayable->sum('total_amount');
        $remaining = $downpayment - $totalPayables;
    @endphp
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-start border-4 border-info" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">{{ $order->order_number }}</h5>
                        <p class="text-muted small mb-0">{{ $order->accountReceivable->submission->salesOrder->so_number ?? '' }}</p>
                    </div>
                    <span class="badge {{ $remaining >= 0 ? 'bg-success' : 'bg-danger' }}">
                        ₱{{ number_format($remaining, 2) }} Rem.
                    </span>
                </div>

                <h6 class="mb-3">{{ $order->accountReceivable->submission->salesOrder->so_name ?? 'Unknown Customer' }}</h6>
                
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block">Downpayment</small>
                        <strong class="text-success">₱{{ number_format($downpayment, 2) }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Payables</small>
                        <strong class="text-danger">₱{{ number_format($totalPayables, 2) }}</strong>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light text-muted">
                <small><i class="bi bi-wallet"></i> Click to view & add payables</small>
            </div>
        </div>
    </div>

    <!-- Order Payables Modal -->
    <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <div>
                        <h5 class="modal-title">{{ $order->order_number }} - Payables Details</h5>
                        <small>{{ $order->accountReceivable->submission->salesOrder->so_name ?? '' }}</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title"><i class="bi bi-cash"></i> Total Downpayment</h6>
                                    <h3>₱{{ number_format($downpayment, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title"><i class="bi bi-cart-dash"></i> Total Payables</h6>
                                    <h3>₱{{ number_format($totalPayables, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card {{ $remaining >= 0 ? 'bg-primary' : 'bg-warning' }} text-white h-100">
                                <div class="card-body text-center">
                                    <h6 class="card-title"><i class="bi bi-wallet2"></i> Remaining Downpayment</h6>
                                    <h3>₱{{ number_format($remaining, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                        <h6 class="mb-0"><i class="bi bi-list-ul"></i> Payables List</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="switchToAddPayableModal{{ $order->id }}()">
                            <i class="bi bi-plus-circle"></i> Add Payable
                        </button>
                    </div>

                    @if($order->accountsPayable->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Notes</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->accountsPayable as $ap)
                                    <tr>
                                        <td>{{ $ap->created_at->format('M d, Y h:i A') }}</td>
                                        <td><span class="badge bg-secondary">{{ ucfirst($ap->vendor_type) }}</span></td>
                                        <td class="text-danger fw-bold">₱{{ number_format($ap->total_amount, 2) }}</td>
                                        <td>{{ $ap->notes ?? '-' }}</td>
                                        <td class="text-end">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary js-open-edit-payable"
                                                data-order-id="{{ $order->id }}"
                                                data-update-action="{{ route('accounts-payable.update', $ap->id) }}"
                                                data-vendor-type="{{ $ap->vendor_type }}"
                                                data-amount="{{ $ap->total_amount }}"
                                                data-notes="{{ $ap->notes }}"
                                                title="Edit"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('accounts-payable.destroy', $ap->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-danger js-confirm-payable-delete"
                                                    data-payable-label="{{ ucfirst($ap->vendor_type) }} (₱{{ number_format($ap->total_amount, 2) }})"
                                                    title="Delete"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No payables recorded for this order yet.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Payable Modal -->
    <div class="modal fade" id="addPayableModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title js-payable-modal-title" data-default-text="Add Payable"><i class="bi bi-plus-circle"></i> Add Payable</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('accounts-payable.store', $order->id) }}" method="POST" class="js-payable-form" data-store-action="{{ route('accounts-payable.store', $order->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Available Downpayment:</strong> ₱{{ number_format($remaining, 2) }}
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payable Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="vendor_type" placeholder="e.g., Cloth, Print, Press, Paper, Plastic" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount (₱) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" step="0.01" min="0.01" required>
                            <small class="text-muted">This will be deducted from the order's downpayment.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="2" placeholder="Any additional details..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="backToOrderModal{{ $order->id }}()">Back</button>
                        <button type="submit" class="btn btn-primary js-payable-submit">
                            <i class="bi bi-check-circle"></i> Save Payable
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function switchToAddPayableModal{{ $order->id }}() {
        const orderModalEl = document.getElementById('orderModal{{ $order->id }}');
        const addPayableModalEl = document.getElementById('addPayableModal{{ $order->id }}');
        
        addPayableModalEl.dataset.returnOrderId = '{{ $order->id }}';

        let orderModal = bootstrap.Modal.getInstance(orderModalEl);
        if (!orderModal) {
            orderModal = new bootstrap.Modal(orderModalEl);
        }
        orderModal.hide();
        
        orderModalEl.addEventListener('hidden.bs.modal', function openAdd() {
            const addModal = new bootstrap.Modal(addPayableModalEl);
            addModal.show();
            orderModalEl.removeEventListener('hidden.bs.modal', openAdd);
        }, { once: true });
    }

    function backToOrderModal{{ $order->id }}() {
        const addPayableModalEl = document.getElementById('addPayableModal{{ $order->id }}');
        addPayableModalEl.dataset.returnOrderId = '{{ $order->id }}';
    }
    </script>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-wallet2 text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No orders found.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmActionModalTitle">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmActionModalBody">Are you sure?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmActionModalConfirmBtn">Proceed</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const confirmModalEl = document.getElementById('confirmActionModal');
    const confirmModal = new bootstrap.Modal(confirmModalEl);
    const confirmTitleEl = document.getElementById('confirmActionModalTitle');
    const confirmBodyEl = document.getElementById('confirmActionModalBody');
    const confirmBtn = document.getElementById('confirmActionModalConfirmBtn');

    let pendingForm = null;
    let restoreModalEl = null;
    let isConfirmed = false;

    function showConfirm(options) {
        pendingForm = options.form;
        restoreModalEl = options.restoreModalEl;
        isConfirmed = false;

        confirmTitleEl.textContent = options.title || 'Confirm Action';
        confirmBodyEl.textContent = options.message || 'Are you sure?';
        confirmBtn.textContent = options.confirmText || 'Proceed';

        confirmBtn.className = 'btn';
        confirmBtn.classList.add(options.confirmBtnClass || 'btn-primary');

        confirmModal.show();
    }

    function confirmForForm(form, options) {
        const currentModalEl = form.closest('.modal');
        if (currentModalEl && currentModalEl.classList.contains('show')) {
            const currentModal = bootstrap.Modal.getInstance(currentModalEl) || new bootstrap.Modal(currentModalEl);
            currentModalEl.dataset.apSuppressOnHideReturn = '1';
            currentModal.hide();
            currentModalEl.addEventListener('hidden.bs.modal', function openConfirm() {
                showConfirm({ ...options, form, restoreModalEl: currentModalEl });
            }, { once: true });
            return;
        }

        showConfirm({ ...options, form, restoreModalEl: null });
    }

    confirmModalEl.addEventListener('hidden.bs.modal', function () {
        if (!isConfirmed && restoreModalEl) {
            delete restoreModalEl.dataset.apSuppressOnHideReturn;
            const modal = bootstrap.Modal.getInstance(restoreModalEl) || new bootstrap.Modal(restoreModalEl);
            modal.show();
            restoreModalEl = null;
        }
        pendingForm = null;
        isConfirmed = false;
        confirmBtn.disabled = false;
    });

    confirmBtn.addEventListener('click', function () {
        if (!pendingForm) return;
        isConfirmed = true;
        confirmBtn.disabled = true;
        pendingForm.submit();
    });

    document.querySelectorAll('.js-payable-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const mode = form.dataset.mode || 'create';
            if (mode === 'edit') {
                confirmForForm(form, {
                    title: 'Update Payable',
                    message: 'Are you sure you want to update this payable?',
                    confirmText: 'Yes, Update',
                    confirmBtnClass: 'btn-primary'
                });
                return;
            }
            confirmForForm(form, {
                title: 'Save Payable',
                message: 'Are you sure you want to save this payable?',
                confirmText: 'Yes, Save',
                confirmBtnClass: 'btn-primary'
            });
        });
    });

    document.querySelectorAll('.js-confirm-payable-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const form = btn.closest('form');
            const label = btn.getAttribute('data-payable-label') || 'this payable';
            confirmForForm(form, {
                title: 'Delete Payable',
                message: `Are you sure you want to delete ${label}?`,
                confirmText: 'Yes, Delete',
                confirmBtnClass: 'btn-danger'
            });
        });
    });

    document.querySelectorAll('.js-open-edit-payable').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const orderId = btn.getAttribute('data-order-id');
            const orderModalEl = document.getElementById(`orderModal${orderId}`);
            const addPayableModalEl = document.getElementById(`addPayableModal${orderId}`);
            if (!orderModalEl || !addPayableModalEl) return;

            const updateAction = btn.getAttribute('data-update-action');
            const vendorType = btn.getAttribute('data-vendor-type') || '';
            const amount = btn.getAttribute('data-amount') || '';
            const notes = btn.getAttribute('data-notes') || '';

            const form = addPayableModalEl.querySelector('form');
            const titleEl = addPayableModalEl.querySelector('.js-payable-modal-title');
            const submitBtn = addPayableModalEl.querySelector('.js-payable-submit');
            if (!form || !titleEl || !submitBtn) return;

            form.dataset.mode = 'edit';
            if (!form.dataset.storeAction) {
                form.dataset.storeAction = form.getAttribute('action') || '';
            }
            if (updateAction) {
                form.setAttribute('action', updateAction);
            }

            const existingMethod = form.querySelector('input[name="_method"]');
            if (!existingMethod) {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            } else {
                existingMethod.value = 'PUT';
            }

            const vendorInput = form.querySelector('input[name="vendor_type"]');
            const amountInput = form.querySelector('input[name="amount"]');
            const notesInput = form.querySelector('textarea[name="notes"]');
            if (vendorInput) vendorInput.value = vendorType;
            if (amountInput) amountInput.value = amount;
            if (notesInput) notesInput.value = notes;

            titleEl.innerHTML = '<i class="bi bi-pencil"></i> Edit Payable';
            submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Update Payable';

            const orderModal = bootstrap.Modal.getInstance(orderModalEl) || new bootstrap.Modal(orderModalEl);
            orderModal.hide();

            orderModalEl.addEventListener('hidden.bs.modal', function openEdit() {
                const addModal = new bootstrap.Modal(addPayableModalEl);
                addPayableModalEl.dataset.returnOrderId = orderId;
                addModal.show();
            }, { once: true });
        });
    });

    function resetAddPayableModal(addPayableModalEl) {
        const form = addPayableModalEl.querySelector('form');
        const titleEl = addPayableModalEl.querySelector('.js-payable-modal-title');
        const submitBtn = addPayableModalEl.querySelector('.js-payable-submit');
        if (!form || !titleEl || !submitBtn) return;

        form.dataset.mode = 'create';
        const storeAction = form.dataset.storeAction || form.getAttribute('data-store-action') || '';
        if (storeAction) form.setAttribute('action', storeAction);

        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();

        titleEl.innerHTML = '<i class="bi bi-plus-circle"></i> Add Payable';
        submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Save Payable';

        const vendorInput = form.querySelector('input[name="vendor_type"]');
        const amountInput = form.querySelector('input[name="amount"]');
        const notesInput = form.querySelector('textarea[name="notes"]');
        if (vendorInput) vendorInput.value = '';
        if (amountInput) amountInput.value = '';
        if (notesInput) notesInput.value = '';
    }

    document.querySelectorAll('[id^="addPayableModal"]').forEach(function (addPayableModalEl) {
        addPayableModalEl.addEventListener('hidden.bs.modal', function () {
            if (addPayableModalEl.dataset.apSuppressOnHideReturn === '1') return;
            resetAddPayableModal(addPayableModalEl);
            const returnOrderId = addPayableModalEl.dataset.returnOrderId;
            if (!returnOrderId) return;
            const orderModalEl = document.getElementById(`orderModal${returnOrderId}`);
            if (!orderModalEl) return;
            const orderModal = bootstrap.Modal.getInstance(orderModalEl) || new bootstrap.Modal(orderModalEl);
            orderModal.show();
            addPayableModalEl.dataset.returnOrderId = '';
        });
    });
});
</script>
@endsection
