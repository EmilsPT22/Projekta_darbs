@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Student Classes</h2>
        <a href="{{ route('internships.index') }}" class="btn btn-secondary">Back to Internships</a>
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
                    @if(request()->filled('class_group_id'))
                        Students in Selected Class
                    @elseif(request()->filled('search'))
                        Search Results
                    @else
                        All Students
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
                    
                    {{-- Class Filter Dropdown --}}
                    @if($classGroups->count() > 0)
                        <select name="class_group_id" class="form-select form-select-sm" style="width: 200px;" id="classFilter">
                            <option value="">-- All Classes --</option>
                            @foreach($classGroups as $class)
                                <option value="{{ $class->id }}" {{ request('class_group_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }} - {{ $class->grade_level }}
                                </option>
                            @endforeach
                        </select>
                    @endif
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
                            <th>Current Class</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <strong>{{ $student->name }}</strong>
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    @if($student->classGroup)
                                        <span class="badge bg-info">{{ $student->classGroup->grade_level }}</span>
                                        <span class="text-muted ms-1">{{ $student->classGroup->name }}</span>
                                    @else
                                        <span class="text-muted">No class assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit-grade', $student->id) }}" class="btn btn-warning btn-sm">
                                        {{ $student->classGroup ? 'Change Class' : 'Assign Class' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    @if(request()->filled('search'))
                                        <i class="bi bi-search display-6 d-block mb-2"></i>
                                        <p>No students found matching "<strong>{{ request('search') }}</strong>"</p>
                                        <a href="{{ route('admin.students-grade') }}" class="btn btn-sm btn-secondary">Clear Search</a>
                                    @elseif(request()->filled('class_group_id'))
                                        <i class="bi bi-people display-6 d-block mb-2"></i>
                                        <p>No students in this class.</p>
                                    @else
                                        <i class="bi bi-people display-6 d-block mb-2"></i>
                                        <p>No students found.</p>
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
            const classFilter = $('#classFilter').val();
            
            debounceTimer = setTimeout(function() {
                performSearch(searchQuery, classFilter);
            }, 500);
        });
        
        // Also update when class filter changes
        $('#classFilter').on('change', function() {
            const searchQuery = $('#searchInput').val();
            const classFilter = $(this).val();
            performSearch(searchQuery, classFilter);
        });
        
        function performSearch(search, classId) {
            $.ajax({
                url: '{{ route("admin.students-grade") }}',
                method: 'GET',
                data: {
                    search: search,
                    class_group_id: classId
                },
                success: function(response) {
                    // Parse the HTML response
                    const $response = $(response);
                    
                    // Update the table body with new results
                    const $newTable = $response.find('table tbody');
                    $('table tbody').html($newTable.html());
                    
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
