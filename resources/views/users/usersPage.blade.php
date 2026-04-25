@extends('layouts.navbar')

@section('content')
<style>
    .page-header {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid var(--bw-border, rgba(0, 0, 0, 0.10));
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    .page-header h2 {
        color: #111;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .page-header p {
        color: rgba(0, 0, 0, 0.6);
        margin: 0;
    }
    .user-card {
        background: rgba(255, 255, 255, 0.92);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--bw-border, rgba(0, 0, 0, 0.10));
    }
    .table thead {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .table thead th {
        color: rgba(0, 0, 0, 0.65);
        font-weight: 700;
        border-bottom: 1px solid rgba(0, 0, 0, 0.10);
        padding: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-size: 0.85rem;
    }
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .btn-add-user {
        background: #111;
        border: 1px solid rgba(0, 0, 0, 0.2);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
        transition: all 0.3s ease;
    }
    .btn-add-user:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.22);
        background: #000;
    }
    .badge-you {
        background: #111;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-weight: 700;
    }
    .modal-content {
        border-radius: 16px;
        border: 1px solid var(--bw-border, rgba(0, 0, 0, 0.10));
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }
    .modal-header {
        background: #111;
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem;
    }
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
    .form-label {
        font-weight: 700;
        color: rgba(0, 0, 0, 0.75);
        margin-bottom: 0.5rem;
    }
    .form-control {
        border: 1px solid rgba(0, 0, 0, 0.14);
        border-radius: 10px;
        padding: 0.75rem;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: rgba(0, 0, 0, 0.65);
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08);
    }
    .btn-submit {
        background: #111;
        border: 1px solid rgba(0, 0, 0, 0.2);
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 700;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.22);
        background: #000;
    }
    .modal-header.edit-mode {
        background: #111;
    }
    .btn-submit.edit-mode {
        background: #111;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
    }
    .btn-submit.edit-mode:hover {
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.22);
        background: #000;
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
            <button type="button" class="btn btn-dark btn-add-user" onclick="openAddModal()">
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
                                <button type="button" class="btn btn-sm btn-outline-dark me-1" 
                                    onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->username }}', '{{ $user->email }}')"
                                    style="border-radius: 8px;">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-dark" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $user->id }}"
                                    style="border-radius: 8px;">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark">
                                                <h5 class="modal-title text-white">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-0">Are you sure you want to delete user <strong>{{ $user->name }}</strong>?</p>
                                                <p class="text-muted small mt-2 mb-0">This action cannot be undone.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-dark">Delete User</button>
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
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark btn-submit" id="submitBtn">
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
