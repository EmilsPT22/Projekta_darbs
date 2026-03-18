@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">User Management</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card bg-dark border-secondary">
        <div class="card-header border-secondary">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0">
                    @if(request()->filled('search'))
                        Search Results
                    @elseif(request()->filled('role'))
                        Users with Role: {{ ucfirst(request('role')) }}
                    @else
                        All Users ({{ $users->count() }})
                    @endif
                </h5>
                <div class="d-flex gap-2 flex-wrap">
                    {{-- Search Input --}}
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control form-control-sm" 
                        placeholder="Search by name or email..." 
                        value="{{ request('search') }}"
                        style="width: 250px;"
                        id="searchInput"
                        autocomplete="off"
                    >
                    
                    {{-- Role Filter Dropdown --}}
                    <select name="role" class="form-select form-select-sm" style="width: 150px;" id="roleFilter">
                        <option value="">-- All Roles --</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="internship_manager" {{ request('role') === 'internship_manager' ? 'selected' : '' }}>Internship Manager</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-dark">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="badge bg-primary me-1">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                                    @empty
                                        <span class="text-muted">No roles</span>
                                    @endforelse
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    @if(request()->filled('search'))
                                        <i class="bi bi-search display-6 d-block mb-2"></i>
                                        <p>No users found matching "<strong>{{ request('search') }}</strong>"</p>
                                    @else
                                        <i class="bi bi-people display-6 d-block mb-2"></i>
                                        <p>No users found.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    // Live search without page refresh
    $(document).ready(function() {
        let debounceTimer;
        
        $('#searchInput').on('input', function() {
            clearTimeout(debounceTimer);
            const searchQuery = $(this).val();
            const roleFilter = $('#roleFilter').val();
            
            debounceTimer = setTimeout(function() {
                performSearch(searchQuery, roleFilter);
            }, 500);
        });
        
        // Also update when role filter changes
        $('#roleFilter').on('change', function() {
            const searchQuery = $('#searchInput').val();
            const roleFilter = $(this).val();
            performSearch(searchQuery, roleFilter);
        });
        
        function performSearch(search, role) {
            $.ajax({
                url: '{{ route("admin.users.index") }}',
                method: 'GET',
                data: {
                    search: search,
                    role: role
                },
                success: function(response) {
                    // Parse the HTML response
                    const $response = $(response);
                    
                    // Update the table body with new results
                    const $newTable = $response.find('#usersTableBody');
                    $('#usersTableBody').html($newTable.html());
                    
                    // Update header text
                    const $newHeader = $response.find('.card-header h5');
                    $('.card-header h5').html($newHeader.html());
                },
                error: function() {
                    alert('Error loading results');
                }
            });
        }
    });
</script>
@endsection
