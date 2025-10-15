document.addEventListener('DOMContentLoaded', function() {
    const masterCheck = document.getElementById('masterCheck');
    let payrollChecks = document.querySelectorAll('.payroll-check');
    const bulkPayBtn = document.getElementById('bulkPayBtn');
    const selectAllBtn = document.getElementById('selectAll');

    function updateBulkPayButton() {
        const checkedBoxes = document.querySelectorAll('.payroll-check:checked');
        bulkPayBtn.disabled = checkedBoxes.length === 0;
    }

    function attachEventListeners() {
        payrollChecks = document.querySelectorAll('.payroll-check');
        payrollChecks.forEach(check => {
            check.addEventListener('change', updateBulkPayButton);
        });
    }

    // Auto-refresh every minute for real-time calculations
    setInterval(function() {
        location.reload();
    }, 60000);

    masterCheck?.addEventListener('change', function() {
        payrollChecks.forEach(check => {
            check.checked = this.checked;
        });
        updateBulkPayButton();
    });

    attachEventListeners();

    payrollChecks.forEach(check => {
        check.addEventListener('change', updateBulkPayButton);
    });

    selectAllBtn?.addEventListener('click', function() {
        const allChecked = Array.from(payrollChecks).every(check => check.checked);
        payrollChecks.forEach(check => {
            check.checked = !allChecked;
        });
        masterCheck.checked = !allChecked;
        updateBulkPayButton();
        this.innerHTML = allChecked ?
            '<i class="fa-solid fa-check-square me-1"></i>Select All' :
            '<i class="fa-solid fa-square me-1"></i>Deselect All';
    });

    document.getElementById('bulkPayForm')?.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.payroll-check:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Please select at least one payroll record.');
        } else {
            return confirm(`Are you sure you want to mark ${checkedBoxes.length} payroll record(s) as paid?`);
        }
    });
});
