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

    //Save attendance (PUT request)
    document.querySelectorAll('.attendance-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Check if inputs are readonly (attendance completed)
            const timeInInput = this.querySelector('.time-in-input');
            const timeOutInput = this.querySelector('.time-out-input');
            
            if (timeInInput.hasAttribute('readonly') || timeOutInput.hasAttribute('readonly')) {
                alert('Cannot edit attendance once time out is recorded.');
                return;
            }

            let employeeId = this.dataset.id;
            let timeIn = timeInInput.value;
            let timeOut = timeOutInput.value;
            let date = this.querySelector('.attendance-date').value;

            console.log(`Saving attendance for employee ${employeeId}`, {timeIn, timeOut, date});

            fetch(`/attendance/${employeeId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    time_in: timeIn && timeIn.trim() !== '' ? timeIn : null,
                    time_out: timeOut && timeOut.trim() !== '' ? timeOut : null,
                    date: date
                })
            })
            .then(async res => {
                if (!res.ok) {
                    const errorData = await res.json();
                    throw new Error(errorData.message || 'Failed to update attendance');
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    alert(`Updated:\nTime In - ${data.time_in || '--'}\nTime Out - ${data.time_out || '--'}`);
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update attendance.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error: ' + err.message);
            });
        });
    });
});
