<div class="modal fade" id="employeeListModal" tabindex="-1" aria-labelledby="employeeListLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="employeeListLabel">
          <i class="fa-solid fa-users me-2"></i>List of Employees
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Hourly Rate</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($allEmployees as $employee)
                <tr>
                  <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                  <td>{{ $employee->phone ?? 'N/A' }}</td>
                  <td>{{ $employee->email ?? 'N/A' }}</td>
                  <td>₱{{ number_format($employee->hourly_rate ?? 0, 2) }}</td>
                  <td>
                    @if($employee->archived_at)
                      <span class="badge bg-danger">Inactive</span>
                    @else
                      <span class="badge bg-success">Active</span>
                    @endif
                  </td>
                  <td>
                    @if($employee->archived_at)
                      <form action="{{ route('employee.restore', $employee->id) }}" method="POST" class="d-inline me-1">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm">Restore</button>
                      </form>
                      <form action="{{ route('employee.forceDelete', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently delete this employee? This cannot be undone!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </form>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
