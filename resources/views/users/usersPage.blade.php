@extends('layouts.navbar')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    .page-header h2 {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .page-header p {
        color: #64748b;
        margin: 0;
    }
    .user-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: none;
    }
    .table thead {
        background-color: #f8fafc;
    }
    .table thead th {
        color: #64748b;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem;
    }
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    .table tbody tr:hover {
        background-color: #f8fafc;
    }
    .btn-add-user {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }
    .btn-add-user:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
    }
    .badge-you {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-weight: 500;
    }
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem;
    }
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    .form-label {
        font-weight: 500;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
    }
    .modal-header.edit-mode {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    .btn-submit.edit-mode {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
    .btn-submit.edit-mode:hover {
        box-shadow: 0 6px 16px rgba(245, 158, 11, 0.5);
    }
</style>

<div class="container-fluid p-4">
    <!-- Header Section -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-people me-2"></i>User Management</h2>
                <p>Manage system users and their access</p>
            </div>
            <button type="button" class="btn btn-primary btn-add-user" onclick="openAddModal()">
                <i class="bi bi-person-plus me-2"></i>Add New User
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Users Table -->
    <div class="card user-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="align-middle">{{ $user->id }}</td>
                            <td class="align-middle">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="badge badge-you">You</span>
                                @endif
                            </td>
                            <td class="align-middle">{{ $user->username }}</td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">{{ $user->created_at->format('M d, Y h:i A') }}</td>
                            <td class="align-middle">
                                @if($user->id !== auth()->id())
                                <button type="button" class="btn btn-sm btn-warning me-1" 
                                    onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->username }}', '{{ $user->email }}')"
                                    style="border-radius: 8px;">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $user->id }}"
                                    style="border-radius: 8px;">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title text-white">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-0">Are you sure you want to delete user <strong>{{ $user->name }}</strong>?</p>
                                                <p class="text-danger small mt-2 mb-0">This action cannot be undone.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete User</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <span class="text-muted small">Cannot delete own account</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- User Modal (Add/Edit) -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">
                <h5 class="modal-title" id="modalTitle">
                    <i class="bi bi-person-plus me-2" id="modalIcon"></i><span id="modalTitleText">Add New User</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="user_name" name="name" 
                               value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="user_username" name="username" 
                               value="{{ old('username') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="user_email" name="email" 
                               value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3" id="passwordField">
                        <label for="user_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="user_password" name="password">
                        <div class="form-text">Must be at least 8 characters. Leave blank to keep current password (edit mode only).</div>
                    </div>
                    <div class="mb-3" id="passwordConfirmField">
                        <label for="user_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="user_password_confirmation" 
                               name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-submit" id="submitBtn">
                        <i class="bi bi-check-lg me-2"></i><span id="submitBtnText">Create User</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddModal() {
    // Reset form
    document.getElementById('userForm').reset();
    document.getElementById('userForm').action = "{{ route('users.store') }}";
    document.getElementById('formMethod').value = 'POST';
    
    // Update modal styling and text for add mode
    document.getElementById('modalHeader').classList.remove('edit-mode');
    document.getElementById('submitBtn').classList.remove('edit-mode');
    document.getElementById('modalIcon').className = 'bi bi-person-plus me-2';
    document.getElementById('modalTitleText').textContent = 'Add New User';
    document.getElementById('submitBtnText').textContent = 'Create User';
    
    // Make password required for add
    document.getElementById('user_password').required = true;
    document.getElementById('user_password_confirmation').required = true;
    document.getElementById('passwordField').querySelector('.form-text').textContent = 'Must be at least 8 characters';
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}

function openEditModal(id, name, username, email) {
    // Fill form with user data
    document.getElementById('user_name').value = name;
    document.getElementById('user_username').value = username;
    document.getElementById('user_email').value = email;
    document.getElementById('user_password').value = '';
    document.getElementById('user_password_confirmation').value = '';
    
    // Update form action and method
    document.getElementById('userForm').action = `/users/${id}`;
    document.getElementById('formMethod').value = 'PUT';
    
    // Update modal styling and text for edit mode
    document.getElementById('modalHeader').classList.add('edit-mode');
    document.getElementById('submitBtn').classList.add('edit-mode');
    document.getElementById('modalIcon').className = 'bi bi-pencil me-2';
    document.getElementById('modalTitleText').textContent = 'Edit User';
    document.getElementById('submitBtnText').textContent = 'Update User';
    
    // Make password optional for edit
    document.getElementById('user_password').required = false;
    document.getElementById('user_password_confirmation').required = false;
    document.getElementById('passwordField').querySelector('.form-text').textContent = 'Must be at least 8 characters. Leave blank to keep current password.';
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
}

@if($errors->any())
// Reopen modal if validation errors exist
document.addEventListener('DOMContentLoaded', function() {
    openAddModal();
});
@endif
</script>

@endsection
