@extends('layout.employee.employee_app')

@section('content')
<div class="d-flex">
    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Attendance – Employee</h4>
            <!-- Trigger Modal -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                Add Employee
            </button>
        </div>

        <!-- Breadcrumb -->
        <p class="text-muted"><a href="{{ route('employee.dashboard') }}">Dashboard </a>/ Attendance</p>

        <!-- Filter bar -->
        <div class="d-flex mb-3 gap-2">
            <button class="btn btn-dark">All Employees</button>
            <button class="btn btn-outline-dark">Filter</button>
            <button class="btn btn-outline-dark">Sort By: Newest</button>
            <input type="text" class="form-control w-25" placeholder="Search">
        </div>

        <!-- Attendance Table -->
        <table class="table table-bordered align-middle" id="attendanceTable">
            <thead class="table-secondary">
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Percentage</th>
                    <th>Yesterday</th>
                    <th>Today</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $index => $employee)
                    @php
                        $total = $employee->attendances->count();
                        $present = $employee->attendances->where('status','Present')->count();
                        $percentage = $total > 0 ? round(($present / $total) * 100) : 0;

                        $yesterday = $employee->attendances->where('date', now()->subDay()->toDateString())->first();
                        $today = $employee->attendances->where('date', now()->toDateString())->first();
                    @endphp

                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $percentage >= 75 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                     role="progressbar" style="width: {{ $percentage }}%">
                                    {{ $percentage }}%
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($yesterday)
                                <span class="badge
                                    {{ $yesterday->status == 'Present' ? 'bg-success' :
                                       ($yesterday->status == 'Late' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $yesterday->status }}
                                </span>
                            @else
                                –
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('attendance.update', $employee->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status"
                                        class="form-select form-select-sm attendance-select"
                                        data-id="{{ $employee->id }}">
                                    <option value="" {{ !$today ? 'selected' : '' }}>-- Select --</option>
                                    <option value="Present" {{ $today && $today->status == 'Present' ? 'selected' : '' }}>Present</option>
                                    <option value="Late" {{ $today && $today->status == 'Late' ? 'selected' : '' }}>Late</option>
                                    <option value="Absent" {{ $today && $today->status == 'Absent' ? 'selected' : '' }}>Absent</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary">Edit</button>
                            <form action="#" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add Employee -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addEmployeeLabel">Add Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{route('employee.store')}}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-success">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.attendance-select').forEach(select => {
    select.addEventListener('change', function() {
        let employeeId = this.dataset.id;
        let status = this.value;

        fetch(`/attendance/${employeeId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log(`Updated: ${data.status}`);
            }
        })
        .catch(err => console.error(err));
    });
});
</script>

@endsection
