@extends("layouts.admin")
@section('title', "Usage Reports")
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Clicks Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
@section('content')
<div id="content" class="p-3" style="background-color: #f8f9fc; margin-left: 250px; width: calc(100% - 220px);">

    <!-- Create a canvas element to render the chart -->
    <div>
        <canvas id="myChart"></canvas>
    </div>
</div>

    <script>
        // Pass the data from PHP to JavaScript
        const labels = {!! json_encode($dates) !!};
        const data = {!! json_encode($totalClicks) !!};

        // Create the chart using Chart.js
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // x-axis labels (dates)
                datasets: [{
                    label: 'Total Clicks',
                    data: data, // y-axis data (clicks)
                    borderColor: '#00aaff', // line color
                    backgroundColor: 'rgba(0, 170, 255, 0.1)', // area color
                    fill: true, // Fill the area under the line
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true,
                    },
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    </script>
@endsection
