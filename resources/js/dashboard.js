document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('hoursChart');
    if (ctx) {
        const employees = ctx.dataset.employees ? ctx.dataset.employees.split(',') : [];
        const hours = ctx.dataset.hours ? ctx.dataset.hours.split(',').map(h => parseFloat(h)) : [];
        
        if (employees.length > 0 && hours.length > 0) {
            const colors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'];
            
            const hoursChart = new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: employees,
                    datasets: [{
                        data: hours,
                        backgroundColor: colors.slice(0, employees.length),
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + ' hours';
                                }
                            }
                        }
                    }
                }
            });
        }
    }
});