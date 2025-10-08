document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchInput');
    const filterDate = document.getElementById('filterDate');
    const sortSelect = document.getElementById('sortSelect');
    const resetFilters = document.getElementById('resetFilters');
    const tbody = document.getElementById('attendanceTbody');

    //Date filter
    filterDate.addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('date', this.value);
        window.location.href = url.toString();
    });

    //Search
    searchInput.addEventListener('input', function() {
        const val = this.value.toLowerCase();
        Array.from(tbody.children).forEach(row => {
            row.style.display = row.dataset.name.includes(val) ? '' : 'none';
        });
    });

    //Sort
    sortSelect.addEventListener('change', function() {
        let rows = Array.from(tbody.children);
        rows.sort((a, b) => {
            let nameA = a.dataset.name;
            let nameB = b.dataset.name;
            return this.value === 'az' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
        });
        rows.forEach(row => tbody.appendChild(row));
    });

    //Reset filters
    resetFilters.addEventListener('click', function() {
        searchInput.value = '';
        Array.from(tbody.children).forEach(row => row.style.display = '');
        sortSelect.value = 'az';
    });

    //One-click attendance toggle
    document.querySelectorAll('.toggle-attendance').forEach(button => {
        button.addEventListener('click', function() {
            const employeeId = this.dataset.id;
            const originalText = this.innerHTML;
            
            // Disable button and show loading
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

            fetch(`/attendance/${employeeId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update time displays
                    if (data.action === 'time_in') {
                        document.getElementById(`time-in-${employeeId}`).textContent = data.time_in;
                        // Add seconds display
                        const timeInSeconds = document.getElementById(`time-in-seconds-${employeeId}`);
                        if (timeInSeconds) {
                            const now = new Date();
                            timeInSeconds.textContent = now.toTimeString().substr(0, 8);
                        }
                        this.innerHTML = '<i class="fa-solid fa-clock"></i> Time Out';
                        this.className = 'btn btn-warning btn-sm toggle-attendance';
                    } else if (data.action === 'time_out') {
                        document.getElementById(`time-out-${employeeId}`).textContent = data.time_out;
                        // Add seconds display and calculate duration
                        const timeOutSeconds = document.getElementById(`time-out-seconds-${employeeId}`);
                        if (timeOutSeconds) {
                            const now = new Date();
                            timeOutSeconds.textContent = now.toTimeString().substr(0, 8);
                        }
                        // Calculate and display duration
                        const timeInElement = document.getElementById(`time-in-${employeeId}`);
                        const timeOutElement = document.getElementById(`time-out-${employeeId}`);
                        const durationElement = document.getElementById(`duration-${employeeId}`);
                        
                        if (timeInElement && timeOutElement && durationElement) {
                            // Simple duration calculation (this will be updated on page refresh for accuracy)
                            durationElement.textContent = 'Calculating...';
                        }
                        
                        this.outerHTML = '<span class="badge bg-success">Completed</span>';
                    }
                    
                    // Show success message
                    const toast = document.createElement('div');
                    toast.className = 'alert alert-success alert-dismissible fade show position-fixed';
                    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                    toast.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(toast);
                    
                    // Auto remove toast after 3 seconds
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 3000);
                } else {
                    alert(data.message);
                    this.innerHTML = originalText;
                }
                this.disabled = false;
            })
            .catch(err => {
                console.error(err);
                alert('Error: Failed to record attendance');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
});
