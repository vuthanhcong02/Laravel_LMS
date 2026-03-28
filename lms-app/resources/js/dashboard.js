document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('dashboardRevenueChart').getContext('2d');

    const labels = window.chartData.labels;
    const data = window.chartData.values;

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Doanh thu (₫)',
            data: data,
            borderWidth: 3,
            borderColor: '#8fc0e0',
            backgroundColor: 'rgba(143, 192, 224, 0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#8fc0e0',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
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
                mode: 'index',
                intersect: false,
                padding: 12,
                backgroundColor: '#1e293b',
                callbacks: {
                    label: function (context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(226, 232, 240, 0.5)',
                    drawBorder: false
                },
                ticks: {
                    callback: function (value) {
                        if (value >= 1000000) return (value / 1000000) + 'M';
                        if (value >= 1000) return (value / 1000) + 'K';
                        return value;
                    },
                    font: { size: 11 }
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: { size: 11 }
                }
            }
        }
    }
});
    });