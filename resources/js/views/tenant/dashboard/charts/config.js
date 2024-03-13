export const lineChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    elements: {
        line: {
            tension: 0
        }
    },
    tooltips: {
        mode: 'index',
        intersect: false,
    },
    scales: {
        yAxes: [
            {
                gridLines: {
                    display: true,
                    lineWidth: 1,
                    color: "rgba(0,0,0,0.1)",
                    drawBorder: false
                },
                ticks: {
                    beginAtZero: true,
                    // stepSize: 5,
                    // min: 50,
                    // max: 70,
                    // padding: 20
                }
            }
        ],
        xAxes: [
            {
                gridLines: {
                    display: true,
                    lineWidth: 1,
                    color: "rgba(0,0,0,0.1)",
                    drawBorder: false
                }
            }
        ]
    },
    legend: {
        display: true,
        position: 'bottom'
    }
};
