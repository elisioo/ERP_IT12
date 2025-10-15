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
                amount = durationValue * 2;
            } else if (timeUnit.value === 'hours') {
                amount = durationValue * 60;
            }
        } else if (deductionType.value === 'violation' && violationAmount.value) {
            amount = parseFloat(violationAmount.value);
        }

        calculatedAmount.textContent = '₱' + amount.toFixed(2);
    }

    deductionType.addEventListener('change', function() {
        lateSection.style.display = this.value === 'late' ? 'block' : 'none';
        violationSection.style.display = this.value === 'violation' ? 'block' : 'none';

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

    document.addEventListener('click', function(e) {
        const target = e.target.closest('[data-bs-target="#deductionModal"]');
        if (target) {
            const payrollId = target.dataset.payrollId;
            const employeeName = target.dataset.employeeName;

            document.getElementById('deductionPayrollId').value = payrollId;
            document.getElementById('deductionEmployeeName').textContent = employeeName;

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
