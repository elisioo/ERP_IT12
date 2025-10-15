document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-bs-target="#deductionHistoryModal"]')) {
            const payrollId = e.target.dataset.payrollId;
            const employeeName = e.target.dataset.employeeName;
            const payrollStatus = e.target.dataset.payrollStatus;

            document.getElementById('historyEmployeeName').textContent = employeeName;

            fetch(`/payroll/${payrollId}/deductions`)
                .then(response => response.json())
                .then(data => {
                    const content = document.getElementById('deductionHistoryContent');

                    if (data.deductions.length === 0) {
                        content.innerHTML = '<div class="alert alert-info">No deductions found for this payroll.</div>';
                        return;
                    }

                    let html = '<div class="table-responsive"><table class="table table-sm">';
                    html += '<thead><tr><th>Type</th><th>Details</th><th>Amount</th><th>Date</th><th>Action</th></tr></thead><tbody>';

                    data.deductions.forEach(deduction => {
                        let details = '';
                        if (deduction.type === 'late') {
                            details = `${deduction.duration} ${deduction.time_unit}`;
                        } else {
                            details = deduction.reason;
                        }

                        const removeButton = payrollStatus === 'paid' ?
                            '<span class="text-muted">Paid</span>' :
                            `<button class="btn btn-sm btn-danger" onclick="removeDeduction(${deduction.id})" title="Remove deduction">
                                <i class="fa-solid fa-trash"></i>
                            </button>`;

                        html += `<tr>
                            <td><span class="badge ${deduction.type === 'late' ? 'bg-warning' : 'bg-danger'}">${deduction.type}</span></td>
                            <td>${details}</td>
                            <td>₱${parseFloat(deduction.amount).toFixed(2)}</td>
                            <td>${new Date(deduction.created_at).toLocaleDateString()}</td>
                            <td>${removeButton}</td>
                        </tr>`;
                    });

                    html += '</tbody></table></div>';
                    html += `<div class="mt-2"><strong>Total Deductions: ₱${data.total.toFixed(2)}</strong></div>`;

                    content.innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('deductionHistoryContent').innerHTML =
                        '<div class="alert alert-danger">Error loading deduction history.</div>';
                });
        }
    });

    window.removeDeduction = function(deductionId) {
        if (confirm('Are you sure you want to remove this deduction?')) {
            fetch(`/payroll/deduction/${deductionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error removing deduction');
                }
            })
            .catch(error => {
                alert('Error removing deduction');
            });
        }
    };
});
