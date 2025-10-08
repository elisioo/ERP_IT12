document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('hoursChart');
    const periodBtns = document.querySelectorAll('.period-btn');
    const hoursList = document.getElementById('hoursList');
    const noDataMessage = document.getElementById('noDataMessage');
    
    // Employee hours data from canvas attributes
    const employeeHoursData = {
        today: JSON.parse(ctx.dataset.today || '[]'),
        week: JSON.parse(ctx.dataset.week || '[]'),
        month: JSON.parse(ctx.dataset.month || '[]')
    };
    
    let currentChart = null;
    
    function updateChart(period) {
        const data = employeeHoursData[period] || [];
        
        // Destroy existing chart
        if (currentChart) {
            currentChart.destroy();
        }
        
        if (data.length > 0) {
            ctx.style.display = 'block';
            noDataMessage.style.display = 'none';
            
            const employees = data.map(emp => emp.name);
            const hours = data.map(emp => emp.hours);
            const colors = ['#FF6B35', '#28a745', '#ffc107', '#dc3545', '#6f42c1'];
            
            currentChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
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
                        legend: { display: false },
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
            
            // Update hours list
            hoursList.innerHTML = data.slice(0, 3).map(emp => 
                `<small class="d-block">${emp.name}: ${emp.hours}h</small>`
            ).join('');
        } else {
            ctx.style.display = 'none';
            noDataMessage.style.display = 'flex';
            document.getElementById('noDataText').textContent = `No hours recorded ${period === 'today' ? 'today' : 'this ' + period}`;
            hoursList.innerHTML = '<small class="text-muted">No data available</small>';
        }
    }
    
    // Period button handlers
    periodBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            periodBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            updateChart(this.dataset.period);
        });
    });
    
    // Initialize with today's data
    updateChart('today');
});