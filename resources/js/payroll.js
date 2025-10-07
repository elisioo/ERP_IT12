document.addEventListener('DOMContentLoaded', function() {
    var editModal = document.getElementById('editRateModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var rate = button.getAttribute('data-rate');
            
            document.getElementById('employeeName').textContent = name;
            document.getElementById('hourlyRate').value = rate;
            document.getElementById('editRateForm').action = '/employee/' + id + '/rate';
        });
    }
});