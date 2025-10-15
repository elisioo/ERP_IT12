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

@vite('resources/js/deduction.js')
