<div class="modal fade" id="deductionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Deduction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="deductionForm" method="POST" action="{{ route('payroll.deduction.store') }}">
                @csrf
                <input type="hidden" name="payroll_id" id="deductionPayrollId">
                <div class="modal-body">
                    <p>Employee: <strong id="deductionEmployeeName"></strong></p>
                    
                    <div class="mb-3">
                        <label class="form-label">Deduction Type</label>
                        <select name="type" id="deductionType" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="late">Late</option>
                            <option value="violation">Violation of Rule</option>
                        </select>
                    </div>

                    <div id="lateSection" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Time Unit</label>
                            <select name="time_unit" id="timeUnit" class="form-control">
                                <option value="minutes">Minutes</option>
                                <option value="hours">Hours</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Duration</label>
                            <input type="number" name="duration" id="duration" class="form-control" min="1">
                        </div>
                    </div>

                    <div id="violationSection" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Enter violation reason"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deduction Amount (₱)</label>
                            <input type="number" name="amount" id="violationAmount" class="form-control" step="0.01" min="0">
                        </div>
                    </div>

                    <div class="alert alert-info mb-3">
                        <strong>Calculated Deduction: <span id="calculatedAmount">₱0.00</span></strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Add Deduction</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deductionType = document.getElementById('deductionType');
    const lateSection = document.getElementById('lateSection');
    const violationSection = document.getElementById('violationSection');
    const timeUnit = document.getElementById('timeUnit');
    const duration = document.getElementById('duration');
    const violationAmount = document.getElementById('violationAmount');
    const calculatedAmount = document.getElementById('calculatedAmount');

    function calculateDeduction() {
        let amount = 0;
        
        if (deductionType.value === 'late' && duration.value) {
            const durationValue = parseFloat(duration.value);
            if (timeUnit.value === 'minutes') {
                amount = durationValue * 2; // 2 pesos per minute
            } else if (timeUnit.value === 'hours') {
                amount = durationValue * 60; // 1 peso per minute = 60 pesos per hour
            }
        } else if (deductionType.value === 'violation' && violationAmount.value) {
            amount = parseFloat(violationAmount.value);
        }
        
        calculatedAmount.textContent = '₱' + amount.toFixed(2);
    }

    deductionType.addEventListener('change', function() {
        lateSection.style.display = this.value === 'late' ? 'block' : 'none';
        violationSection.style.display = this.value === 'violation' ? 'block' : 'none';
        
        // Reset form fields when switching types
        if (this.value === 'late') {
            violationAmount.value = '';
            document.getElementById('reason').value = '';
        } else if (this.value === 'violation') {
            duration.value = '';
        }
        
        calculateDeduction();
    });

    timeUnit.addEventListener('change', calculateDeduction);
    duration.addEventListener('input', calculateDeduction);
    violationAmount.addEventListener('input', calculateDeduction);

    // Form validation
    document.getElementById('deductionForm').addEventListener('submit', function(e) {
        const type = deductionType.value;
        let isValid = true;
        
        if (type === 'late') {
            if (!duration.value || duration.value <= 0) {
                alert('Please enter a valid duration for late deduction.');
                isValid = false;
            }
        } else if (type === 'violation') {
            if (!document.getElementById('reason').value.trim()) {
                alert('Please enter a reason for the violation.');
                isValid = false;
            }
            if (!violationAmount.value || violationAmount.value <= 0) {
                alert('Please enter a valid deduction amount for violation.');
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });

    // Modal trigger handler
    document.addEventListener('click', function(e) {
        const target = e.target.closest('[data-bs-target="#deductionModal"]');
        if (target) {
            const payrollId = target.dataset.payrollId;
            const employeeName = target.dataset.employeeName;
            
            document.getElementById('deductionPayrollId').value = payrollId;
            document.getElementById('deductionEmployeeName').textContent = employeeName;
            
            // Reset form
            deductionType.value = '';
            lateSection.style.display = 'none';
            violationSection.style.display = 'none';
            duration.value = '';
            violationAmount.value = '';
            document.getElementById('reason').value = '';
            calculatedAmount.textContent = '₱0.00';
        }
    });
});
</script>