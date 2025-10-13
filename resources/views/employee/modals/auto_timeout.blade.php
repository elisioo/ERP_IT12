<div class="modal fade" id="autoTimeoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Auto Time-Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="autoTimeoutForm" method="POST" action="{{ route('attendance.autoTimeout') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Employees</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllEmployees">
                            <label class="form-check-label" for="selectAllEmployees">
                                <strong>Select All Active Employees</strong>
                            </label>
                        </div>
                        <hr>
                        <div id="employeeCheckboxes" style="max-height: 200px; overflow-y: auto;">
                            @foreach($employees as $employee)
                                @php
                                    $attendance = $employee->attendances->first();
                                    $isActive = $attendance && $attendance->time_in && !$attendance->time_out;
                                @endphp
                                @if($isActive)
                                    <div class="form-check">
                                        <input class="form-check-input employee-checkbox" type="checkbox" 
                                               name="employee_ids[]" value="{{ $employee->id }}" 
                                               id="emp{{ $employee->id }}">
                                        <label class="form-check-label" for="emp{{ $employee->id }}">
                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                            <small class="text-muted">(In since {{ \Carbon\Carbon::parse($attendance->time_in)->format('g:i A') }})</small>
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Time-Out Option</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="timeout_option" id="immediateTimeout" value="immediate" checked>
                            <label class="form-check-label" for="immediateTimeout">
                                Time out immediately
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="timeout_option" id="scheduledTimeout" value="scheduled">
                            <label class="form-check-label" for="scheduledTimeout">
                                Schedule automatic time-out
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Time-Out Time</label>
                        <input type="time" name="timeout_time" id="timeoutTime" class="form-control" required>
                        <small class="text-muted" id="timeoutHelp">Set the time when employees should be automatically timed out</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Set Auto Time-Out</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllEmployees');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    const timeoutTimeInput = document.getElementById('timeoutTime');
    
    // Set current time as default
    const now = new Date();
    timeoutTimeInput.value = now.toTimeString().slice(0, 5);
    
    // Handle timeout option changes
    const immediateOption = document.getElementById('immediateTimeout');
    const scheduledOption = document.getElementById('scheduledTimeout');
    const timeoutHelp = document.getElementById('timeoutHelp');
    
    function updateTimeoutHelp() {
        if (immediateOption.checked) {
            timeoutHelp.textContent = 'Set the time when employees should be automatically timed out';
        } else {
            timeoutHelp.textContent = 'Set the time to wait for automatic time-out (system will wait until this time)';
        }
    }
    
    immediateOption.addEventListener('change', updateTimeoutHelp);
    scheduledOption.addEventListener('change', updateTimeoutHelp);
    updateTimeoutHelp();
    
    // Check for scheduled timeouts every minute
    setInterval(function() {
        fetch('/attendance/check-scheduled-timeouts')
            .then(response => response.json())
            .then(data => {
                if (data.processed > 0) {
                    console.log(`Processed ${data.processed} scheduled timeouts`);
                    // Optionally refresh the page or show notification
                    if (window.location.pathname.includes('attendance')) {
                        location.reload();
                    }
                }
            })
            .catch(error => console.log('Scheduled timeout check error:', error));
    }, 60000); // Check every minute
    
    // Select all functionality
    selectAllCheckbox?.addEventListener('change', function() {
        employeeCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Update select all when individual checkboxes change
    employeeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(employeeCheckboxes).every(cb => cb.checked);
            const noneChecked = Array.from(employeeCheckboxes).every(cb => !cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = !allChecked && !noneChecked;
        });
    });
});
</script>