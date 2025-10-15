document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllEmployees');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    const timeoutTimeInput = document.getElementById('timeoutTime');
    const now = new Date();
    timeoutTimeInput.value = now.toTimeString().slice(0, 5);
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
    }, 60000);

    selectAllCheckbox?.addEventListener('change', function() {
        employeeCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    employeeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(employeeCheckboxes).every(cb => cb.checked);
            const noneChecked = Array.from(employeeCheckboxes).every(cb => !cb.checked);

            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = !allChecked && !noneChecked;
        });
    });

    const refreshBtn = document.getElementById('refreshEmployees');
    refreshBtn?.addEventListener('click', function() {
        refreshBtn.disabled = true;
        refreshBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Refreshing...';
        fetch(window.location.href, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newEmployeeCheckboxes = doc.getElementById('employeeCheckboxes');

            if (newEmployeeCheckboxes) {
                document.getElementById('employeeCheckboxes').innerHTML = newEmployeeCheckboxes.innerHTML;

                const newEmployeeCheckboxesEls = document.querySelectorAll('.employee-checkbox');
                newEmployeeCheckboxesEls.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(newEmployeeCheckboxesEls).every(cb => cb.checked);
                        const noneChecked = Array.from(newEmployeeCheckboxesEls).every(cb => !cb.checked);

                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = !allChecked && !noneChecked;
                    });
                });

                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
                refreshBtn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Refreshed!';
                setTimeout(() => {
                    refreshBtn.innerHTML = '<i class="fa-solid fa-refresh me-1"></i> Refresh';
                    refreshBtn.disabled = false;
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Refresh error:', error);
            refreshBtn.innerHTML = '<i class="fa-solid fa-exclamation-triangle me-1"></i> Error';
            setTimeout(() => {
                refreshBtn.innerHTML = '<i class="fa-solid fa-refresh me-1"></i> Refresh';
                refreshBtn.disabled = false;
            }, 2000);
        });
    });
});
