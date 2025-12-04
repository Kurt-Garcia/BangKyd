@extends('layouts.navbar')

@section('content')
<div class="container-fluid" style="background: #f5f7fa; min-height: 100vh; padding: 2rem;">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header Section -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1" style="color: #2d3748; font-weight: 600;">
                            <i class="bi bi-box-seam me-2" style="color: #667eea;"></i>Product Management
                        </h2>
                        <p class="text-muted mb-0">Manage your products and pricing</p>
                    </div>
                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#productModal" onclick="openAddModal()" style="background: #667eea; color: white; border-radius: 10px; padding: 0.6rem 1.5rem; box-shadow: 0 4px 6px rgba(102, 126, 234, 0.2);">
                        <i class="bi bi-plus-circle me-1"></i>Add Product
                    </button>
                </div>
            </div>

            <div class="card border-0" style="border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success border-0 m-4" style="background: #d4edda; border-radius: 10px;" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                <tr>
                                    <th width="5%" class="py-3 ps-4" style="color: #64748b; font-weight: 600; font-size: 0.875rem;">#</th>
                                    <th width="30%" class="py-3" style="color: #64748b; font-weight: 600; font-size: 0.875rem;">Product Name</th>
                                    <th width="15%" class="py-3" style="color: #64748b; font-weight: 600; font-size: 0.875rem;">Price</th>
                                    <th width="30%" class="py-3" style="color: #64748b; font-weight: 600; font-size: 0.875rem;">Description</th>
                                    <th width="10%" class="py-3" style="color: #64748b; font-weight: 600; font-size: 0.875rem;">Status</th>
                                    <th width="10%" class="py-3 pe-4 text-center" style="color: #64748b; font-weight: 600; font-size: 0.875rem;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td class="ps-4 py-3">
                                            <span style="color: #94a3b8; font-weight: 500;">{{ $loop->iteration }}</span>
                                        </td>
                                        <td class="py-3">
                                            <strong style="color: #1e293b; font-size: 0.95rem;">{{ $product->name }}</strong>
                                        </td>
                                        <td class="py-3">
                                            <span style="background: #ecfdf5; color: #059669; padding: 0.35rem 0.75rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem;">
                                                ₱{{ number_format($product->price, 2) }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <span style="color: #64748b; font-size: 0.875rem;">
                                                {{ $product->description ?? 'No description' }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            @if($product->is_active)
                                                <span style="background: #dbeafe; color: #1e40af; padding: 0.35rem 0.65rem; border-radius: 6px; font-weight: 500; font-size: 0.8rem;">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Active
                                                </span>
                                            @else
                                                <span style="background: #f1f5f9; color: #64748b; padding: 0.35rem 0.65rem; border-radius: 6px; font-weight: 500; font-size: 0.8rem;">
                                                    <i class="bi bi-dash-circle-fill me-1"></i>Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center py-3 pe-4">
                                            <button type="button"
                                               class="btn btn-sm me-1" 
                                               style="background: #fef3c7; color: #92400e; border: none; border-radius: 8px; padding: 0.4rem 0.75rem;"
                                               data-bs-toggle="modal"
                                               data-bs-target="#productModal"
                                               onclick="openEditModal({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ addslashes($product->description ?? '') }}', {{ $product->is_active ? 'true' : 'false' }})"
                                               title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm" 
                                                    style="background: #fee2e2; color: #991b1b; border: none; border-radius: 8px; padding: 0.4rem 0.75rem;"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $product->id }}"
                                                    title="Delete">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                                                        <div class="modal-header" style="background: #fef2f2; border-bottom: 1px solid #fee2e2;">
                                                            <h5 class="modal-title" style="color: #991b1b; font-weight: 600;">
                                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Delete
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <p style="color: #1e293b;">Are you sure you want to delete <strong>{{ $product->name }}</strong>?</p>
                                                            <div class="alert alert-danger border-0" style="background: #fee2e2; color: #991b1b; border-radius: 8px;">
                                                                <small><i class="bi bi-info-circle me-1"></i>This action cannot be undone.</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer" style="border-top: 1px solid #f1f5f9;">
                                                            <button type="button" class="btn" style="background: #f1f5f9; color: #475569; border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn" style="background: #dc2626; color: white; border-radius: 8px;">Delete Product</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div style="color: #94a3b8;">
                                                <i class="bi bi-inbox" style="font-size: 3.5rem; color: #cbd5e1;"></i>
                                                <p class="mt-3 mb-1" style="color: #64748b; font-weight: 500;">No products found</p>
                                                <p class="text-muted small">Add your first product to get started</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header" style="background: white; border-bottom: 1px solid #f1f5f9;">
                    <h5 class="modal-title" id="modalTitle" style="color: #1e293b; font-weight: 600;">
                        <i class="bi bi-plus-circle me-2" id="modalIcon" style="color: #667eea;"></i>
                        <span id="modalTitleText">Add New Product</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="productForm" method="POST">
                    @csrf
                    <input type="hidden" id="method" name="_method" value="POST">
                    
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label for="name" class="form-label" style="color: #475569; font-weight: 600; font-size: 0.9rem;">
                                Product Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.7rem 1rem; font-size: 0.95rem;"
                                   id="name" 
                                   name="name" 
                                   placeholder="e.g., Jersey, Shorts, Polo"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="form-label" style="color: #475569; font-weight: 600; font-size: 0.9rem;">
                                Price <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: #f8fafc; border: 1px solid #e2e8f0; border-right: none; border-radius: 10px 0 0 10px; color: #64748b;">₱</span>
                                <input type="number" 
                                       class="form-control" 
                                       style="border: 1px solid #e2e8f0; border-left: none; border-radius: 0 10px 10px 0; padding: 0.7rem 1rem; font-size: 0.95rem;"
                                       id="price" 
                                       name="price" 
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label" style="color: #475569; font-weight: 600; font-size: 0.9rem;">
                                Description (Optional)
                            </label>
                            <textarea class="form-control" 
                                      style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 0.7rem 1rem; font-size: 0.95rem;"
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Enter product description..."></textarea>
                        </div>

                        <div id="activeStatusDiv" class="mb-3 p-3" style="background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; display: none;">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       style="width: 3rem; height: 1.5rem; cursor: pointer;"
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active"
                                       value="1">
                                <label class="form-check-label ms-2" for="is_active" style="color: #475569; font-weight: 500; cursor: pointer;">
                                    <i class="bi bi-check-circle me-1" style="color: #059669;"></i>Active Status
                                    <small class="d-block text-muted mt-1">Make this product available for selection</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" style="border-top: 1px solid #f1f5f9;">
                        <button type="button" class="btn" style="background: #f1f5f9; color: #475569; border-radius: 8px;" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn" id="submitBtn" style="background: #667eea; color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(102, 126, 234, 0.2);">
                            <i class="bi bi-check-circle me-1"></i>
                            <span id="submitBtnText">Add Product</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let editMode = false;
let editProductId = null;

function openAddModal() {
    editMode = false;
    editProductId = null;
    
    document.getElementById('modalTitleText').textContent = 'Add New Product';
    document.getElementById('modalIcon').className = 'bi bi-plus-circle me-2';
    document.getElementById('modalIcon').style.color = '#667eea';
    document.getElementById('submitBtnText').textContent = 'Add Product';
    document.getElementById('submitBtn').style.background = '#667eea';
    document.getElementById('submitBtn').style.boxShadow = '0 4px 6px rgba(102, 126, 234, 0.2)';
    
    document.getElementById('productForm').action = '{{ route("products.store") }}';
    document.getElementById('method').value = 'POST';
    document.getElementById('activeStatusDiv').style.display = 'none';
    
    // Clear form
    document.getElementById('name').value = '';
    document.getElementById('price').value = '';
    document.getElementById('description').value = '';
    document.getElementById('is_active').checked = true;
}

function openEditModal(id, name, price, description, isActive) {
    editMode = true;
    editProductId = id;
    
    document.getElementById('modalTitleText').textContent = 'Edit Product';
    document.getElementById('modalIcon').className = 'bi bi-pencil me-2';
    document.getElementById('modalIcon').style.color = '#f59e0b';
    document.getElementById('submitBtnText').textContent = 'Update Product';
    document.getElementById('submitBtn').style.background = '#f59e0b';
    document.getElementById('submitBtn').style.boxShadow = '0 4px 6px rgba(245, 158, 11, 0.2)';
    
    document.getElementById('productForm').action = '{{ url("products") }}/' + id;
    document.getElementById('method').value = 'PUT';
    document.getElementById('activeStatusDiv').style.display = 'block';
    
    // Fill form
    document.getElementById('name').value = name;
    document.getElementById('price').value = price;
    document.getElementById('description').value = description;
    document.getElementById('is_active').checked = isActive;
}

@if($errors->any())
    // Reopen modal if there are validation errors
    document.addEventListener('DOMContentLoaded', function() {
        @if(old('_method') == 'PUT')
            // Edit mode - need to get product ID from URL or session
            var modal = new bootstrap.Modal(document.getElementById('productModal'));
            modal.show();
        @else
            openAddModal();
            var modal = new bootstrap.Modal(document.getElementById('productModal'));
            modal.show();
        @endif
    });
@endif
</script>
@endpush

@endsection
