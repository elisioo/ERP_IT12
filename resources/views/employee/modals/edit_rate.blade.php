<div class="modal fade" id="editRateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Hourly Rate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRateForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Employee: <strong id="employeeName"></strong></p>
                    <div class="mb-3">
                        <label class="form-label">Hourly Rate (₱)</label>
                        <input type="number" step="0.01" name="hourly_rate" id="hourlyRate" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>